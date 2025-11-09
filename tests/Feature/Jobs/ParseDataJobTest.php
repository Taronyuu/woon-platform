<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ParseDataJob;
use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\PropertyUnit;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParseDataJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_parses_property_data_and_creates_property_unit(): void
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

        $content = '<h1>Beautiful House</h1>€ 450.000 3 kamers 2 slaapkamers bouwjaar 1990';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/detail/koop/amsterdam/12345-straat-1/',
            'url_hash' => md5('https://www.funda.nl/detail/koop/amsterdam/12345-straat-1/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ParseDataJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $this->assertEquals(1, PropertyUnit::count());

        $property = PropertyUnit::first();
        $this->assertEquals('sale', $property->transaction_type);
        $this->assertEquals('Beautiful House', $property->title);
    }

    public function test_syncs_website_relationship_with_pivot_data(): void
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

        $content = '<h1>Test Property</h1>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/detail/koop/amsterdam/12345-straat/',
            'url_hash' => md5('https://www.funda.nl/detail/koop/amsterdam/12345-straat/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ParseDataJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $property = PropertyUnit::first();
        $this->assertCount(1, $property->websites);
        $this->assertEquals('12345', $property->websites->first()->pivot->external_id);
        $this->assertEquals($crawledPage->url, $property->websites->first()->pivot->source_url);
    }

    public function test_increments_properties_extracted_counter(): void
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
            'properties_extracted' => 0,
        ]);

        $content = '<h1>Property</h1>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/detail/koop/rotterdam/99999-test/',
            'url_hash' => md5('https://www.funda.nl/detail/koop/rotterdam/99999-test/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ParseDataJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $crawlJob->refresh();

        $this->assertEquals(1, $crawlJob->properties_extracted);
    }

    public function test_does_nothing_for_non_detail_pages(): void
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
            'properties_extracted' => 0,
        ]);

        $content = '<h1>Search Results</h1>';

        $crawledPage = CrawledPage::create([
            'crawl_job_id' => $crawlJob->id,
            'url' => 'https://www.funda.nl/koop/amsterdam/',
            'url_hash' => md5('https://www.funda.nl/koop/amsterdam/'),
            'content' => $content,
            'scraped_at' => now(),
        ]);

        $job = new ParseDataJob($crawlJob->id, $crawledPage->id);
        $job->handle();

        $this->assertEquals(0, PropertyUnit::count());

        $crawlJob->refresh();
        $this->assertEquals(0, $crawlJob->properties_extracted);
    }
}
