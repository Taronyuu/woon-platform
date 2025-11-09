<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ExtractLinksJob;
use App\Jobs\ParseDataJob;
use App\Jobs\ScrapePageJob;
use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\Website;
use App\Services\FirecrawlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class ScrapePageJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_scrapes_page_and_updates_crawled_page(): void
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
            'status' => 'running',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
        ]);

        $firecrawlMock = Mockery::mock(FirecrawlService::class);
        $firecrawlMock->shouldReceive('scrape')
            ->once()
            ->andReturn([
                'markdown' => 'Test content',
                'html' => '<html>Test</html>',
                'links' => ['https://www.funda.nl/detail/123/'],
                'metadata' => ['statusCode' => 200],
            ]);

        $this->app->instance(FirecrawlService::class, $firecrawlMock);

        $job = new ScrapePageJob($crawlJob->id, $crawledPage->id, $crawledPage->url);
        $job->handle($firecrawlMock);

        $crawledPage->refresh();

        $this->assertEquals('Test content', $crawledPage->content);
        $this->assertEquals('<html>Test</html>', $crawledPage->raw_html);
        $this->assertNotNull($crawledPage->scraped_at);
    }

    public function test_dispatches_extract_links_and_parse_data_jobs(): void
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
            'status' => 'running',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
        ]);

        $firecrawlMock = Mockery::mock(FirecrawlService::class);
        $firecrawlMock->shouldReceive('scrape')
            ->once()
            ->andReturn([
                'markdown' => 'Test content',
                'html' => '<html>Test</html>',
                'links' => [],
                'metadata' => ['statusCode' => 200],
            ]);

        $this->app->instance(FirecrawlService::class, $firecrawlMock);

        $job = new ScrapePageJob($crawlJob->id, $crawledPage->id, $crawledPage->url);
        $job->handle($firecrawlMock);

        Queue::assertPushed(ExtractLinksJob::class);
        Queue::assertPushed(ParseDataJob::class);
    }

    public function test_increments_pages_crawled_counter(): void
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
            'status' => 'running',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
        ]);

        $firecrawlMock = Mockery::mock(FirecrawlService::class);
        $firecrawlMock->shouldReceive('scrape')
            ->once()
            ->andReturn([
                'markdown' => 'Test content',
                'html' => '<html>Test</html>',
                'links' => [],
                'metadata' => ['statusCode' => 200],
            ]);

        $this->app->instance(FirecrawlService::class, $firecrawlMock);

        $job = new ScrapePageJob($crawlJob->id, $crawledPage->id, $crawledPage->url);
        $job->handle($firecrawlMock);

        $crawlJob->refresh();

        $this->assertEquals(1, $crawlJob->pages_crawled);
        $this->assertEquals(1, $crawlJob->total_requests);
    }

    public function test_skips_already_scraped_pages(): void
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
            'status' => 'running',
        ]);

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
            'scraped_at' => now(),
        ]);

        $firecrawlMock = Mockery::mock(FirecrawlService::class);
        $firecrawlMock->shouldNotReceive('scrape');

        $this->app->instance(FirecrawlService::class, $firecrawlMock);

        $job = new ScrapePageJob($crawlJob->id, $crawledPage->id, $crawledPage->url);
        $job->handle($firecrawlMock);

        Queue::assertNothingPushed();
    }
}
