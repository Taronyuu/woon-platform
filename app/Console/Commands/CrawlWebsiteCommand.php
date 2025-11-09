<?php

namespace App\Console\Commands;

use App\Jobs\InitiateCrawlJob;
use App\Models\CrawlJob;
use App\Models\Website;
use Illuminate\Console\Command;

class CrawlWebsiteCommand extends Command
{
    protected $signature = 'crawl:website
                            {website? : The website ID or name to crawl}
                            {--all : Crawl all active websites}
                            {--list : List all available websites}';

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
        $websites = Website::all();

        if ($websites->isEmpty()) {
            $this->warn('No websites configured yet.');
            $this->line('');
            $this->info('Create a website first:');
            $this->line('  Website::create([');
            $this->line('    \'name\' => \'Funda\',');
            $this->line('    \'base_url\' => \'https://www.funda.nl\',');
            $this->line('    \'crawler_class\' => \'App\\Crawlers\\FundaCrawler\',');
            $this->line('    \'max_depth\' => 3,');
            $this->line('    \'delay_ms\' => 1000,');
            $this->line('    \'use_flaresolverr\' => true,');
            $this->line('    \'start_urls\' => [\'https://www.funda.nl/koop/heel-nederland/\'],');
            $this->line('    \'is_active\' => true,');
            $this->line('  ]);');
            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Name', 'Base URL', 'Crawler', 'Active'],
            $websites->map(fn($w) => [
                $w->id,
                $w->name,
                $w->base_url,
                class_basename($w->crawler_class),
                $w->is_active ? '✓' : '✗',
            ])
        );

        return self::SUCCESS;
    }

    protected function findWebsite(string $identifier): ?Website
    {
        if (is_numeric($identifier)) {
            return Website::find($identifier);
        }

        return Website::where('name', 'like', "%{$identifier}%")->first();
    }

    protected function crawlAllWebsites(): int
    {
        $websites = Website::where('is_active', true)->get();

        if ($websites->isEmpty()) {
            $this->warn('No active websites found.');
            return self::FAILURE;
        }

        $this->info("Crawling {$websites->count()} active website(s)...");
        $this->line('');

        foreach ($websites as $website) {
            $this->crawlWebsite($website);
            $this->line('');
        }

        return self::SUCCESS;
    }

    protected function crawlWebsite(Website $website): int
    {
        $this->info("Starting crawl for: {$website->name}");
        $this->line("Base URL: {$website->base_url}");
        $this->line("Crawler: {$website->crawler_class}");
        $this->line('');

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
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
            $crawlJob->refresh();

            $this->info('Crawl completed!');
            $this->line('');
            $this->displayStats($crawlJob);
        } else {
            $this->info('Jobs are being processed by queue worker.');
            $this->line("Monitor progress: php artisan queue:monitor crawl-{$website->id}");
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
