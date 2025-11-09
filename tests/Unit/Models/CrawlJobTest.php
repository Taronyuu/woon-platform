<?php

namespace Tests\Unit\Models;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawlJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_website_relationship(): void
    {
        $website = Website::create([
            'name' => 'Test Website',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(Website::class, $crawlJob->website);
        $this->assertEquals('Test Website', $crawlJob->website->name);
    }

    public function test_crawled_pages_relationship(): void
    {
        $website = Website::create([
            'name' => 'Test Website',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://example.com/page1',
            'url_hash' => md5('https://example.com/page1'),
        ]);

        $this->assertCount(1, $crawlJob->crawledPages);
        $this->assertEquals('https://example.com/page1', $crawlJob->crawledPages->first()->url);
    }

    public function test_status_values(): void
    {
        $website = Website::create([
            'name' => 'Test Website',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com'],
        ]);

        $pendingJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $runningJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'running',
        ]);

        $completedJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'completed',
        ]);

        $this->assertEquals('pending', $pendingJob->status);
        $this->assertEquals('running', $runningJob->status);
        $this->assertEquals('completed', $completedJob->status);
    }

    public function test_casts_datetime_fields(): void
    {
        $website = Website::create([
            'name' => 'Test Website',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com'],
        ]);

        $crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'running',
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $crawlJob->started_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $crawlJob->completed_at);
    }
}
