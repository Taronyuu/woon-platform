<?php

namespace App\Console\Commands;

use App\Models\DiscoveredUrl;
use App\Services\ScrapflyService;
use Illuminate\Console\Command;

class DiscoverUrlsCommand extends Command
{
    protected $signature = 'crawl:discover
                            {website? : The website ID to discover URLs for}
                            {--full : Crawl ALL start URLs}
                            {--partial : Stop when existing URL found (default)}
                            {--list : List all available websites}';

    protected $description = 'Discover property URLs from website listing pages';

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
        $startUrls = $config['start_urls'] ?? [];
        $shouldCrawl = $config['should_crawl'] ?? fn() => true;
        $extractLinks = $config['extract_links'] ?? fn() => [];
        $delayMs = $config['delay_ms'] ?? 1000;

        $this->info("Starting " . ($isFullCrawl ? 'FULL' : 'PARTIAL') . " discovery for: {$config['name']}");
        $this->line("Start URLs: " . count($startUrls));
        $this->line('');

        $urlsDiscovered = 0;
        $urlsSkipped = 0;
        $startUrlsProcessed = 0;

        foreach ($startUrls as $startUrl) {
            $this->line("Processing: {$startUrl}");

            try {
                $data = $scrapfly->scrape($startUrl);
                $links = $extractLinks($data['html'], $startUrl);

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
                        $this->displayStats($urlsDiscovered, $urlsSkipped, $startUrlsProcessed);
                        return self::SUCCESS;
                    }

                    if (!$exists) {
                        DiscoveredUrl::createFromUrl($websiteId, $link);
                        $urlsDiscovered++;
                        $newOnPage++;
                    } else {
                        $urlsSkipped++;
                    }
                }

                $this->info("  Found {$linksOnPage} valid links, {$newOnPage} new");
                $startUrlsProcessed++;

            } catch (\Exception $e) {
                $this->error("  Failed: {$e->getMessage()}");
            }

            usleep($delayMs * 1000);
        }

        $this->line('');
        $this->info('Discovery complete!');
        $this->displayStats($urlsDiscovered, $urlsSkipped, $startUrlsProcessed);

        return self::SUCCESS;
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

    protected function displayStats(int $discovered, int $skipped, int $startUrlsProcessed): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Start URLs Processed', $startUrlsProcessed],
                ['New URLs Discovered', $discovered],
                ['URLs Skipped (existing)', $skipped],
            ]
        );
    }
}
