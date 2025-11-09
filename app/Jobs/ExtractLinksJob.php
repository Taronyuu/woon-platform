<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $crawlJobId,
        public int $crawledPageId
    ) {
    }

    public function handle(): void
    {
        $crawledPage = CrawledPage::findOrFail($this->crawledPageId);
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);

        $crawlerClass = $crawlJob->website->crawler_class;
        $crawler = new $crawlerClass($crawlJob);

        $linksFromContent = $crawler->extractLinks(
            $crawledPage->content ?? '',
            $crawledPage->url
        );

        $linksFromFirecrawl = collect($crawledPage->links ?? []);

        $allLinks = $linksFromContent
            ->merge($linksFromFirecrawl)
            ->filter(fn($url) => !empty($url) && is_string($url))
            ->filter(fn($url) => filter_var($url, FILTER_VALIDATE_URL) !== false)
            ->filter(fn($url) => !str_contains($url, 'javascript:'))
            ->filter(fn($url) => !preg_match('/^#/', $url))
            ->filter(fn($url) => $crawler->shouldCrawl($url))
            ->unique()
            ->values();

        $newLinksCount = 0;

        foreach ($allLinks as $link) {
            $urlHash = md5($link);

            $exists = CrawledPage::where('crawl_job_id', $crawlJob->id)
                ->where('url_hash', $urlHash)
                ->exists();

            if (!$exists) {
                $newPage = CrawledPage::create([
                    'crawl_job_id' => $crawlJob->id,
                    'url' => $link,
                    'url_hash' => $urlHash,
                ]);

                ScrapePageJob::dispatch($crawlJob->id, $newPage->id, $link);
                $newLinksCount++;
            }
        }

        $crawlJob->increment('links_found', $newLinksCount);
    }
}
