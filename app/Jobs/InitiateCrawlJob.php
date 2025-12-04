<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        $startUrls = $crawler->getStartUrls();
        shuffle($startUrls);

        if ($crawlJob->property_limit) {
            $startUrlCount = max(1, (int) ceil($crawlJob->property_limit / 10));
            $startUrls = array_slice($startUrls, 0, $startUrlCount);
        }

        \Log::info("InitiateCrawlJob: Starting crawl for {$crawler->getName()}", [
            'crawl_job_id' => $crawlJob->id,
            'website_id' => $crawlJob->website_id,
            'property_limit' => $crawlJob->property_limit,
            'start_urls_count' => count($startUrls),
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

            \Log::info("InitiateCrawlJob: Dispatching initial scrape", [
                'url' => $normalizedUrl,
                'page_id' => $crawledPage->id,
            ]);

            ScrapePageJob::dispatch($crawlJob->id, $crawledPage->id, $normalizedUrl);
        }
    }
}
