<?php

namespace Tests\Feature\Console;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\PropertyUnit;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CrawlWebsiteCommandIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_crawl_workflow_with_mocked_api(): void
    {
        Http::fake([
            '*funda.nl/koop/heel-nederland*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => "# Woningen te koop\n\n[Huis Amsterdam](/koop/amsterdam/detail/12345)\n[Villa Utrecht](/koop/utrecht/detail/67890)",
                    'html' => '<html><body><h1>Woningen te koop</h1></body></html>',
                    'links' => [
                        'https://www.funda.nl/koop/amsterdam/detail/12345',
                        'https://www.funda.nl/koop/utrecht/detail/67890',
                    ],
                    'metadata' => [
                        'title' => 'Woningen te koop',
                        'statusCode' => 200,
                    ],
                ],
            ], 200),
            '*detail/12345*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => "# Prachtige woning in Amsterdam\n\n€ 450.000\n\nAdres: Hoofdstraat 123, 1012AB Amsterdam\n\nWoonoppervlakte: 120 m²\nPerceel: 200 m²\nKamers: 4\nSlaapkamers: 3\nBadkamers: 2\nBouwjaar: 1985\nEnergielabel: B",
                    'html' => '<html><body><h1>Prachtige woning</h1></body></html>',
                    'links' => [],
                    'metadata' => [
                        'title' => 'Prachtige woning in Amsterdam',
                        'statusCode' => 200,
                    ],
                ],
            ], 200),
            '*detail/67890*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => "# Luxe villa in Utrecht\n\n€ 750.000\n\nAdres: Parkweg 456, 3511AA Utrecht\n\nWoonoppervlakte: 200 m²\nPerceel: 500 m²\nKamers: 6\nSlaapkamers: 4\nBadkamers: 3\nBouwjaar: 2010\nEnergielabel: A",
                    'html' => '<html><body><h1>Luxe villa</h1></body></html>',
                    'links' => [],
                    'metadata' => [
                        'title' => 'Luxe villa in Utrecht',
                        'statusCode' => 200,
                    ],
                ],
            ], 200),
        ]);

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 0,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website', ['website' => $website->id])
            ->expectsOutput("Starting crawl for: {$website->name}")
            ->assertSuccessful();

        $crawlJob = CrawlJob::latest()->first();

        $this->assertEquals('running', $crawlJob->status);
        $this->assertEquals(3, $crawlJob->pages_crawled);
        $this->assertEquals(2, $crawlJob->links_found);
        $this->assertEquals(2, $crawlJob->properties_extracted);

        $this->assertEquals(3, CrawledPage::where('crawl_job_id', $crawlJob->id)->count());

        $properties = PropertyUnit::all();
        $this->assertEquals(2, $properties->count());

        $amsterdamProperty = $properties->where('address_city', 'Amsterdam')->first();
        $this->assertNotNull($amsterdamProperty);
        $this->assertEquals('Prachtige woning in Amsterdam', $amsterdamProperty->title);
        $this->assertEquals('sale', $amsterdamProperty->transaction_type);
        $this->assertEquals(450000, $amsterdamProperty->buyprice);

        $utrechtProperty = $properties->where('address_city', 'Utrecht')->first();
        $this->assertNotNull($utrechtProperty);
        $this->assertEquals('Luxe villa in Utrecht', $utrechtProperty->title);
        $this->assertEquals('sale', $utrechtProperty->transaction_type);
        $this->assertEquals(750000, $utrechtProperty->buyprice);
    }

    public function test_crawl_displays_final_stats_in_sync_mode(): void
    {
        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => 'Test content',
                    'html' => '<html><body>Test</body></html>',
                    'links' => [],
                    'metadata' => ['statusCode' => 200],
                ],
            ], 200),
        ]);

        $website = Website::create([
            'name' => 'Test Site',
            'base_url' => 'https://test.com',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 1,
            'delay_ms' => 0,
            'use_flaresolverr' => false,
            'start_urls' => ['https://test.com/page'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website', ['website' => $website->id])
            ->expectsOutput('Running in SYNC mode - this will block until complete')
            ->expectsOutput('Crawl completed!')
            ->expectsOutputToContain('Status')
            ->expectsOutputToContain('Pages Crawled')
            ->assertSuccessful();
    }
}
