<?php

namespace Tests\Feature\Jobs;

use App\Jobs\InitiateCrawlJob;
use App\Jobs\ScrapePageJob;
use App\Models\CrawlJob;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InitiateCrawlJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_initiates_crawl_and_updates_status(): void
    {
        Queue::fake();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $job = new InitiateCrawlJob($crawlJob->id);
        $job->handle();

        $crawlJob->refresh();

        $this->assertEquals('running', $crawlJob->status);
        $this->assertNotNull($crawlJob->started_at);
    }

    public function test_creates_crawled_pages_for_start_urls(): void
    {
        Queue::fake();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/', 'https://www.funda.nl/huur/heel-nederland/'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $job = new InitiateCrawlJob($crawlJob->id);
        $job->handle();

        $this->assertCount(2, $crawlJob->crawledPages);
    }

    public function test_dispatches_scrape_page_jobs(): void
    {
        Queue::fake();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/', 'https://www.funda.nl/huur/heel-nederland/'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $job = new InitiateCrawlJob($crawlJob->id);
        $job->handle();

        Queue::assertPushed(ScrapePageJob::class, 2);
    }
}
