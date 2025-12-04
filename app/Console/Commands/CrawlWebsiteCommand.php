<?php

namespace App\Console\Commands;

use App\Jobs\InitiateCrawlJob;
use App\Models\CrawlJob;
use Illuminate\Console\Command;

class CrawlWebsiteCommand extends Command
{
    protected $signature = 'crawl:website
                            {website? : The website ID or name to crawl}
                            {--all : Crawl all active websites}
                            {--list : List all available websites}
                            {--limit= : Limit the number of properties to extract}';

    protected $description = 'Crawl a website using the configured crawler';

    public function handle(): int
    {
        if ($this->option('list')) {
            return $this->listWebsites();
        }

        if ($this->option('all')) {
            return $this->crawlAllWebsites();
        }

        $websiteIdentifier = $this->argument('website');

        if (!$websiteIdentifier) {
            $this->error('Please provide a website ID/name or use --list to see available websites');
            return self::FAILURE;
        }

        $website = $this->findWebsite($websiteIdentifier);

        if (!$website) {
            $this->error("Website not found: {$websiteIdentifier}");
            $this->line('');
            $this->info('Available websites:');
            $this->listWebsites();
            return self::FAILURE;
        }

        return $this->crawlWebsite($website);
    }

    protected function listWebsites(): int
    {
        $websites = config('websites', []);

        if (empty($websites)) {
            $this->warn('No websites configured yet.');
            $this->line('');
            $this->info('Add websites in config/websites.php');
            return self::SUCCESS;
        }

        $rows = [];
        foreach ($websites as $id => $config) {
            $rows[] = [
                $id,
                $config['name'] ?? 'Unknown',
                $config['base_url'] ?? 'N/A',
                count($config['start_urls'] ?? []),
                ($config['is_active'] ?? true) ? '✓' : '✗',
            ];
        }

        $this->table(
            ['ID', 'Name', 'Base URL', 'Start URLs', 'Active'],
            $rows
        );

        return self::SUCCESS;
    }

    protected function findWebsite(string $identifier): ?string
    {
        $websites = config('websites', []);

        if (isset($websites[$identifier])) {
            return $identifier;
        }

        foreach ($websites as $id => $config) {
            if (stripos($config['name'] ?? '', $identifier) !== false) {
                return $id;
            }
        }

        return null;
    }

    protected function crawlAllWebsites(): int
    {
        $websites = collect(config('websites', []))
            ->filter(fn($config) => $config['is_active'] ?? true);

        if ($websites->isEmpty()) {
            $this->warn('No active websites found.');
            return self::FAILURE;
        }

        $this->info("Crawling {$websites->count()} active website(s)...");
        $this->line('');

        foreach ($websites->keys() as $websiteId) {
            $this->crawlWebsite($websiteId);
            $this->line('');
        }

        return self::SUCCESS;
    }

    protected function crawlWebsite(string $websiteId): int
    {
        $config = config("websites.{$websiteId}");

        if (!$config) {
            $this->error("Website configuration not found: {$websiteId}");
            return self::FAILURE;
        }

        $this->info("Starting crawl for: {$config['name']}");
        $this->line("Base URL: {$config['base_url']}");
        $this->line("Website ID: {$websiteId}");
        $this->line('');

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $crawlJob = CrawlJob::create([
            'website_id' => $websiteId,
            'property_limit' => $limit,
            'status' => 'pending',
        ]);

        $this->info("Created CrawlJob #{$crawlJob->id}");
        $this->line('');

        if (config('queue.default') === 'sync') {
            $this->warn('Running in SYNC mode - this will block until complete');
        } else {
            $this->info('Jobs dispatched to queue: ' . config('queue.default'));
        }

        $this->line('');

        InitiateCrawlJob::dispatch($crawlJob->id);

        $this->line('Crawl initiated. Monitoring progress...');
        $this->line('');

        if (config('queue.default') === 'sync') {
            $previousPagesCrawled = 0;
            $previousPagesFound = 0;
            $startTime = now();
            $lastActivityTime = now();
            $idleThreshold = 10;

            while (true) {
                $crawlJob->refresh();

                $totalPages = $crawlJob->crawledPages()->count();
                $scrapedPages = $crawlJob->crawledPages()->whereNotNull('scraped_at')->count();

                if ($crawlJob->pages_crawled > $previousPagesCrawled) {
                    $latestPages = $crawlJob->crawledPages()
                        ->orderBy('scraped_at', 'desc')
                        ->limit($crawlJob->pages_crawled - $previousPagesCrawled)
                        ->get();

                    foreach ($latestPages as $page) {
                        $status = $page->status_code ?? 'pending';
                        $this->line("  [{$page->depth}] {$status} - {$page->url}");
                    }

                    $previousPagesCrawled = $crawlJob->pages_crawled;
                    $lastActivityTime = now();
                }

                if ($crawlJob->crawledPages()->count() > $previousPagesFound) {
                    $newCount = $crawlJob->crawledPages()->count() - $previousPagesFound;
                    $this->comment("  +{$newCount} new pages discovered");
                    $previousPagesFound = $crawlJob->crawledPages()->count();
                    $lastActivityTime = now();
                }

                if (in_array($crawlJob->status, ['completed', 'failed'])) {
                    break;
                }

                if ($totalPages > 0 && $totalPages === $scrapedPages && $lastActivityTime->diffInSeconds(now()) >= $idleThreshold) {
                    $this->line('');
                    $this->info('All pages scraped, marking as completed...');
                    $crawlJob->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                    break;
                }

                if ($startTime->diffInMinutes(now()) >= 30) {
                    $this->line('');
                    $this->warn('Crawl timeout reached (30 minutes)');
                    break;
                }

                sleep(1);
            }

            $this->line('');
            $this->info('Crawl completed!');
            $this->line('');
            $this->displayStats($crawlJob);
        } else {
            $this->info('Jobs are being processed by queue worker.');
            $this->line("Monitor progress: php artisan queue:monitor crawl-{$websiteId}");
            $this->line("View job: CrawlJob::find({$crawlJob->id})");
        }

        return self::SUCCESS;
    }

    protected function displayStats(CrawlJob $crawlJob): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Status', $crawlJob->status],
                ['Pages Crawled', $crawlJob->pages_crawled],
                ['Pages Failed', $crawlJob->pages_failed],
                ['Links Found', $crawlJob->links_found],
                ['Properties Extracted', $crawlJob->properties_extracted],
                ['Total Requests', $crawlJob->total_requests],
                ['Started At', $crawlJob->started_at?->format('Y-m-d H:i:s')],
                ['Completed At', $crawlJob->completed_at?->format('Y-m-d H:i:s')],
            ]
        );
    }
}
