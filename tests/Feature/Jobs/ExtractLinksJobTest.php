<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ExtractLinksJob;
use App\Jobs\ScrapePageJob;
use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ExtractLinksJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_extracts_links_and_creates_new_pages(): void
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

        $content = '<a href="https://www.funda.nl/koop/amsterdam/">Amsterdam</a><a href="https://www.funda.nl/koop/rotterdam/">Rotterdam</a>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/heel-nederland/',
            'url_hash' => md5('https://www.funda.nl/koop/heel-nederland/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ExtractLinksJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $this->assertCount(3, $crawlJob->crawledPages);
    }

    public function test_dispatches_scrape_jobs_for_new_links(): void
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

        $content = '<a href="https://www.funda.nl/koop/amsterdam/">Amsterdam</a><a href="https://www.funda.nl/koop/rotterdam/">Rotterdam</a>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/heel-nederland/',
            'url_hash' => md5('https://www.funda.nl/koop/heel-nederland/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ExtractLinksJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        Queue::assertPushed(ScrapePageJob::class, 2);
    }

    public function test_increments_links_found_counter(): void
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

        $content = '<a href="https://www.funda.nl/koop/amsterdam/">Amsterdam</a><a href="https://www.funda.nl/koop/rotterdam/">Rotterdam</a>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/heel-nederland/',
            'url_hash' => md5('https://www.funda.nl/koop/heel-nederland/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ExtractLinksJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $crawlJob->refresh();

        $this->assertEquals(2, $crawlJob->links_found);
    }

    public function test_does_not_create_duplicate_pages(): void
    {
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

        $existingPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
        ]);

        $content = '<a href="https://www.funda.nl/koop/amsterdam/">Amsterdam</a>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/heel-nederland/',
            'url_hash' => md5('https://www.funda.nl/koop/heel-nederland/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ExtractLinksJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $this->assertCount(2, $crawlJob->crawledPages);
        $crawlJob->refresh();
        $this->assertEquals(0, $crawlJob->links_found);
    }
}
