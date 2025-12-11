<?php

namespace App\Console\Commands;

use App\Models\DiscoveredUrl;
use App\Services\ScrapflyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DiscoverUrlsCommand extends Command
{
    protected $signature = 'crawl:discover
                            {website? : The website ID to discover URLs for}
                            {--full : Crawl ALL start URLs}
                            {--from= : Start from this start URL index (0-based)}
                            {--till= : End at this start URL index (exclusive)}
                            {--concurrency=1 : Number of concurrent requests}
                            {--partial : Stop when existing URL found (default)}
                            {--list : List all available websites}';

    protected $description = 'Discover property URLs from website listing pages';

    private int $urlsDiscovered = 0;
    private int $urlsSkipped = 0;
    private int $startUrlsProcessed = 0;

    public function handle(ScrapflyService $scrapfly): int
    {
        if ($this->option('list')) {
            return $this->listWebsites();
        }

        $websiteId = $this->argument('website');

        if (!$websiteId) {
            $this->error('Please provide a website ID or use --list to see available websites');
            return self::FAILURE;
        }

        $config = config("websites.{$websiteId}");

        if (!$config) {
            $this->error("Website not found: {$websiteId}");
            $this->line('');
            $this->listWebsites();
            return self::FAILURE;
        }

        $isFullCrawl = $this->option('full');
        $fromIndex = $this->option('from') ? (int) $this->option('from') : 0;
        $tillIndex = $this->option('till') ? (int) $this->option('till') : null;
        $concurrency = (int) $this->option('concurrency') ?: 1;
        $startUrls = $config['start_urls'] ?? [];
        $shouldCrawl = $config['should_crawl'] ?? fn() => true;
        $extractLinks = $config['extract_links'] ?? fn() => [];

        if ($fromIndex > 0) {
            $startUrls = array_slice($startUrls, $fromIndex);
        }

        if ($tillIndex !== null) {
            $length = $tillIndex - $fromIndex;
            $startUrls = array_slice($startUrls, 0, $length);
        }

        $rangeInfo = '';
        if ($fromIndex > 0 || $tillIndex !== null) {
            $rangeInfo = " (index {$fromIndex}" . ($tillIndex !== null ? " to {$tillIndex}" : '') . ")";
        }

        $this->info("Starting " . ($isFullCrawl ? 'FULL' : 'PARTIAL') . " discovery for: {$config['name']}");
        $this->line("Start URLs: " . count($startUrls) . $rangeInfo);
        $this->line("Concurrency: {$concurrency}");
        $this->line('');

        if ($concurrency > 1) {
            $this->processWithConcurrency($startUrls, $websiteId, $scrapfly, $extractLinks, $shouldCrawl, $isFullCrawl, $concurrency);
        } else {
            $this->processSequentially($startUrls, $websiteId, $scrapfly, $extractLinks, $shouldCrawl, $isFullCrawl, $config['delay_ms'] ?? 1000);
        }

        $this->line('');
        $this->info('Discovery complete!');
        $this->displayStats();

        return self::SUCCESS;
    }

    protected function processSequentially(array $startUrls, string $websiteId, ScrapflyService $scrapfly, callable $extractLinks, callable $shouldCrawl, bool $isFullCrawl, int $delayMs): void
    {
        foreach ($startUrls as $startUrl) {
            $this->line("Processing: {$startUrl}");

            try {
                $data = $scrapfly->scrape($startUrl);
                $shouldStop = $this->processPageLinks($data['html'], $startUrl, $websiteId, $extractLinks, $shouldCrawl, $isFullCrawl);

                if ($shouldStop) {
                    return;
                }

                $this->startUrlsProcessed++;

            } catch (\Exception $e) {
                $this->error("  Failed: {$e->getMessage()}");
            }

            usleep($delayMs * 1000);
        }
    }

    protected function processWithConcurrency(array $startUrls, string $websiteId, ScrapflyService $scrapfly, callable $extractLinks, callable $shouldCrawl, bool $isFullCrawl, int $concurrency): void
    {
        $chunks = array_chunk($startUrls, $concurrency);
        $apiKey = config('services.scrapfly.api_key');
        $baseUrl = 'https://api.scrapfly.io/scrape';

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->line("Processing batch " . ($chunkIndex + 1) . "/" . count($chunks) . " (" . count($chunk) . " URLs)");

            $responses = Http::pool(fn ($pool) =>
                collect($chunk)->map(fn ($url) =>
                    $pool->timeout(120)->get($baseUrl, [
                        'key' => $apiKey,
                        'url' => $url,
                        'country' => 'nl',
                        'asp' => true,
                        'timeout' => 75000,
                    ])
                )->toArray()
            );

            foreach ($responses as $index => $response) {
                $startUrl = $chunk[$index];

                try {
                    if (!$response->successful()) {
                        $this->error("  Failed [{$startUrl}]: HTTP {$response->status()}");
                        continue;
                    }

                    $data = $response->json();
                    if (!isset($data['result']['content'])) {
                        $this->error("  Failed [{$startUrl}]: No content");
                        continue;
                    }

                    $html = $data['result']['content'];
                    $shouldStop = $this->processPageLinks($html, $startUrl, $websiteId, $extractLinks, $shouldCrawl, $isFullCrawl);

                    if ($shouldStop) {
                        return;
                    }

                    $this->startUrlsProcessed++;

                } catch (\Exception $e) {
                    $this->error("  Failed [{$startUrl}]: {$e->getMessage()}");
                }
            }
        }
    }

    protected function processPageLinks(string $html, string $startUrl, string $websiteId, callable $extractLinks, callable $shouldCrawl, bool $isFullCrawl): bool
    {
        $links = $extractLinks($html, $startUrl);

        $linksOnPage = 0;
        $newOnPage = 0;

        foreach ($links as $link) {
            if (!$shouldCrawl($link)) {
                continue;
            }

            $linksOnPage++;
            $exists = DiscoveredUrl::urlExists($websiteId, $link);

            if (!$isFullCrawl && $exists) {
                $this->warn("Found existing URL, stopping partial crawl");
                $this->line("URL: {$link}");
                $this->line('');
                $this->displayStats();
                return true;
            }

            if (!$exists) {
                DiscoveredUrl::createFromUrl($websiteId, $link);
                $this->urlsDiscovered++;
                $newOnPage++;
            } else {
                $this->urlsSkipped++;
            }
        }

        $this->info("  [{$startUrl}] Found {$linksOnPage} valid links, {$newOnPage} new");
        return false;
    }

    protected function listWebsites(): int
    {
        $websites = config('websites', []);

        if (empty($websites)) {
            $this->warn('No websites configured.');
            return self::SUCCESS;
        }

        $rows = [];
        foreach ($websites as $id => $config) {
            $pendingCount = DiscoveredUrl::query()
                ->forWebsite($id)
                ->pending()
                ->count();

            $totalCount = DiscoveredUrl::query()
                ->forWebsite($id)
                ->count();

            $rows[] = [
                $id,
                $config['name'] ?? 'Unknown',
                count($config['start_urls'] ?? []),
                $totalCount,
                $pendingCount,
                ($config['is_active'] ?? true) ? '✓' : '✗',
            ];
        }

        $this->table(
            ['ID', 'Name', 'Start URLs', 'Discovered', 'Pending', 'Active'],
            $rows
        );

        return self::SUCCESS;
    }

    protected function displayStats(): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Start URLs Processed', $this->startUrlsProcessed],
                ['New URLs Discovered', $this->urlsDiscovered],
                ['URLs Skipped (existing)', $this->urlsSkipped],
            ]
        );
    }
}
