<?php

namespace App\Jobs;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Services\ScrapflyService;
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

    public function handle(ScrapflyService $scrapfly): void
    {
        $crawledPage = CrawledPage::findOrFail($this->crawledPageId);
        $crawlJob = CrawlJob::findOrFail($this->crawlJobId);

        if ($crawledPage->scraped_at && $crawledPage->raw_html) {
            \Log::info("ScrapePageJob: Using cached HTML for {$this->url}");
            ExtractLinksJob::dispatch($crawlJob->id, $crawledPage->id);
            ParseDataJob::dispatch($crawlJob->id, $crawledPage->id);
            return;
        }

        sleep(rand(2, 5));

        \Log::info("ScrapePageJob: Scraping {$this->url}", [
            'attempt' => $this->attempts(),
        ]);

        try {
            $data = $scrapfly->scrape($this->url);

            $crawledPage->update([
                'raw_html' => $data['html'],
                'status_code' => $data['status_code'],
                'scraped_at' => now(),
            ]);

            \Log::info("ScrapePageJob: Successfully scraped {$this->url}", [
                'status_code' => $data['status_code'],
                'duration' => $data['duration'],
                'html_length' => strlen($data['html']),
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

            if (str_contains($e->getMessage(), '408') || str_contains($e->getMessage(), '429')) {
                if ($this->attempts() < $this->tries) {
                    $this->release($this->backoff[$this->attempts() - 1] ?? 120);
                    return;
                }
            }

            throw $e;
        }
    }
}
