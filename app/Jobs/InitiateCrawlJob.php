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
        $crawlerClass = $crawlJob->website->crawler_class;
        $crawler = new $crawlerClass($crawlJob);

        $crawlJob->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        foreach ($crawler->getStartUrls() as $url) {
            $urlHash = md5($url);

            $crawledPage = CrawledPage::create([
                'crawl_job_id' => $crawlJob->id,
                'url' => $url,
                'url_hash' => $urlHash,
            ]);

            ScrapePageJob::dispatch($crawlJob->id, $crawledPage->id, $url);
        }
    }
}
