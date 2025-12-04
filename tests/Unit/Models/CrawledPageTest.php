<?php

namespace Tests\Unit\Models;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawledPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_crawl_job_relationship(): void
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

        $this->assertInstanceOf(CrawlJob::class, $crawledPage->crawlJob);
        $this->assertEquals($crawlJob->id, $crawledPage->crawlJob->id);
    }

    public function test_casts_datetime_fields(): void
    {
        $crawlJob = CrawlJob::create([
            'website_id' => 'funda',
            'status' => 'pending',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://example.com/page1',
            'url_hash' => md5('https://example.com/page1'),
            'scraped_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $crawledPage->scraped_at);
    }
}
