<?php

namespace Tests\Unit\Models;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawledPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_crawl_job_relationship(): void
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

        $this->assertInstanceOf(CrawlJob::class, $crawledPage->crawlJob);
        $this->assertEquals($crawlJob->id, $crawledPage->crawlJob->id);
    }

    public function test_casts_array_fields(): void
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
            'links' => ['https://example.com/page2', 'https://example.com/page3'],
            'metadata' => ['title' => 'Test Page', 'description' => 'Test Description'],
        ]);

        $this->assertIsArray($crawledPage->links);
        $this->assertIsArray($crawledPage->metadata);
        $this->assertCount(2, $crawledPage->links);
        $this->assertEquals('Test Page', $crawledPage->metadata['title']);
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
