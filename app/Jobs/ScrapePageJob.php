<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Services\FirecrawlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapePageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $maxExceptions = 3;
    public $timeout = 300;
    public $backoff = [10, 30, 60, 120];

    public function __construct(
        public int $crawlJobId,
        public int $crawledPageId,
        public string $url
    ) {
        $crawlJob = CrawlJob::find($crawlJobId);
        $this->onQueue('crawl-' . ($crawlJob?->website_id ?? 'default'));
    }

    public function retryUntil()
    {
        return now()->addMinutes(30);
    }

    public function handle(FirecrawlService $firecrawl): void
    {
        $crawledPage = CrawledPage::findOrFail($this->crawledPageId);
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);

        if ($crawledPage->scraped_at) {
            return;
        }

        sleep(rand(2, 5));

        $crawler = $crawlJob->getCrawler();

        $useFlaresolverr = $crawler->shouldUseFlaresolverr();
        \Log::info("ScrapePageJob: Scraping {$this->url}", [
            'use_flaresolverr' => $useFlaresolverr,
            'depth' => $crawledPage->depth,
            'attempt' => $this->attempts(),
        ]);

        try {
            $data = $firecrawl->scrape(
                $this->url,
                ['markdown', 'links', 'html'],
                true,
                $useFlaresolverr
            );

            $crawledPage->update([
                'content' => $data['markdown'] ?? null,
                'raw_html' => $data['html'] ?? null,
                'links' => $data['links'] ?? [],
                'metadata' => $data['metadata'] ?? [],
                'status_code' => $data['metadata']['statusCode'] ?? 200,
                'scraped_at' => now(),
            ]);

            \Log::info("ScrapePageJob: Successfully scraped {$this->url}", [
                'status_code' => $data['metadata']['statusCode'] ?? 200,
                'links_found' => count($data['links'] ?? []),
                'content_length' => strlen($data['markdown'] ?? ''),
            ]);

            ExtractLinksJob::dispatch($crawlJob->id, $crawledPage->id);
            ParseDataJob::dispatch($crawlJob->id, $crawledPage->id);

            $crawlJob->increment('pages_crawled');
            $crawlJob->increment('total_requests');

        } catch (\Exception $e) {
            \Log::error("ScrapePageJob: Failed to scrape {$this->url}", [
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);
            $crawlJob->increment('pages_failed');

            if (str_contains($e->getMessage(), '408')) {
                if ($this->attempts() < $this->tries) {
                    $this->release($this->backoff[$this->attempts() - 1] ?? 120);
                    return;
                }
            }

            throw $e;
        }
    }
}
