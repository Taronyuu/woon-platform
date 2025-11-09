<?php

namespace Tests\Feature\Console;

use App\Models\CrawlJob;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CrawlWebsiteCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_websites_when_list_option_provided(): void
    {
        Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website --list')
            ->expectsTable(
                ['ID', 'Name', 'Base URL', 'Crawler', 'Active'],
                [
                    [1, 'Funda', 'https://www.funda.nl', 'FundaCrawler', '✓'],
                ]
            )
            ->assertSuccessful();
    }

    public function test_shows_message_when_no_websites_exist(): void
    {
        $this->artisan('crawl:website --list')
            ->expectsOutput('No websites configured yet.')
            ->assertSuccessful();
    }

    public function test_crawls_website_by_id(): void
    {
        Queue::fake();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website', ['website' => $website->id])
            ->expectsOutput("Starting crawl for: {$website->name}")
            ->assertSuccessful();

        $this->assertDatabaseHas('crawl_jobs', [
            'website_id' => $website->id,
            'status' => 'pending',
        ]);
    }

    public function test_crawls_website_by_name(): void
    {
        Queue::fake();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website', ['website' => 'Funda'])
            ->expectsOutput("Starting crawl for: {$website->name}")
            ->assertSuccessful();

        $this->assertDatabaseHas('crawl_jobs', [
            'website_id' => $website->id,
        ]);
    }

    public function test_shows_error_when_website_not_found(): void
    {
        $this->artisan('crawl:website', ['website' => 999])
            ->expectsOutput('Website not found: 999')
            ->assertFailed();
    }

    public function test_requires_website_argument_or_option(): void
    {
        $this->artisan('crawl:website')
            ->expectsOutput('Please provide a website ID/name or use --list to see available websites')
            ->assertFailed();
    }

    public function test_crawls_all_active_websites(): void
    {
        Queue::fake();

        Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/'],
            'is_active' => true,
        ]);

        Website::create([
            'name' => 'Inactive Site',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => ['https://example.com'],
            'is_active' => false,
        ]);

        $this->artisan('crawl:website --all')
            ->expectsOutput('Crawling 1 active website(s)...')
            ->assertSuccessful();

        $this->assertEquals(1, CrawlJob::count());
    }
}
