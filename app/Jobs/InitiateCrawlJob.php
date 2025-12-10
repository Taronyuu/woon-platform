<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class InitiateCrawlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $crawlJobId)
    {
    }

    public function handle(): void
    {
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);
        $crawler = $crawlJob->getCrawler();
        $websiteId = $crawlJob->website_id;

        $allStartUrls = $crawler->getStartUrls();
        $startUrls = $this->getUnexploredStartUrls($websiteId, $allStartUrls);

        shuffle($startUrls);

        if ($crawlJob->property_limit) {
            $startUrlCount = max(1, (int) ceil($crawlJob->property_limit / 10));
            $startUrls = array_slice($startUrls, 0, $startUrlCount);
        }

        $cacheKey = "explored_start_urls:{$websiteId}";
        $exploredCount = count(Cache::get($cacheKey, []));

        \Log::info("InitiateCrawlJob: Starting crawl for {$crawler->getName()}", [
            'crawl_job_id' => $crawlJob->id,
            'website_id' => $websiteId,
            'property_limit' => $crawlJob->property_limit,
            'start_urls_count' => count($startUrls),
            'exploration_progress' => "{$exploredCount}/" . count($allStartUrls),
            'max_depth' => $crawler->getMaxDepth(),
            'use_flaresolverr' => $crawler->shouldUseFlaresolverr(),
        ]);

        $crawlJob->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        foreach ($startUrls as $url) {
            $normalizedUrl = preg_replace('/#.*$/', '', $url);
            $urlHash = md5($normalizedUrl);

            $crawledPage = CrawledPage::create([
                'crawl_job_id' => $crawlJob->id,
                'url' => $normalizedUrl,
                'url_hash' => $urlHash,
            ]);

            $this->markUrlExplored($websiteId, $urlHash);

            \Log::info("InitiateCrawlJob: Dispatching initial scrape", [
                'url' => $normalizedUrl,
                'page_id' => $crawledPage->id,
            ]);

            ScrapePageJob::dispatch($crawlJob->id, $crawledPage->id, $normalizedUrl);
        }
    }

    protected function getUnexploredStartUrls(string $websiteId, array $allStartUrls): array
    {
        $cacheKey = "explored_start_urls:{$websiteId}";
        $exploredHashes = Cache::get($cacheKey, []);

        $unexploredUrls = array_filter($allStartUrls, function ($url) use ($exploredHashes) {
            $normalizedUrl = preg_replace('/#.*$/', '', $url);
            $urlHash = md5($normalizedUrl);
            return !in_array($urlHash, $exploredHashes);
        });

        if (empty($unexploredUrls)) {
            \Log::info("InitiateCrawlJob: All start URLs explored, resetting cycle", [
                'website_id' => $websiteId,
                'total_urls' => count($allStartUrls),
            ]);
            Cache::forget($cacheKey);
            return $allStartUrls;
        }

        return array_values($unexploredUrls);
    }

    protected function markUrlExplored(string $websiteId, string $urlHash): void
    {
        $cacheKey = "explored_start_urls:{$websiteId}";
        $exploredHashes = Cache::get($cacheKey, []);
        $exploredHashes[] = $urlHash;
        Cache::forever($cacheKey, $exploredHashes);
    }
}
