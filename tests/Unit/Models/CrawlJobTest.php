<?php

namespace Tests\Unit\Models;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawlJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_crawled_pages_relationship(): void
    {
        $crawlJob = CrawlJob::create([
            'website_id' => 'funda',
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
        $pendingJob = CrawlJob::create([
            'website_id' => 'funda',
            'status' => 'pending',
        ]);

        $runningJob = CrawlJob::create([
            'website_id' => 'funda',
            'status' => 'running',
        ]);

        $completedJob = CrawlJob::create([
            'website_id' => 'funda',
            'status' => 'completed',
        ]);

        $this->assertEquals('pending', $pendingJob->status);
        $this->assertEquals('running', $runningJob->status);
        $this->assertEquals('completed', $completedJob->status);
    }

    public function test_casts_datetime_fields(): void
    {
        $crawlJob = CrawlJob::create([
            'website_id' => 'funda',
            'status' => 'running',
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $crawlJob->started_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $crawlJob->completed_at);
    }
}
