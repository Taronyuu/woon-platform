<?php

namespace Tests\Feature;

use App\Jobs\ExtractLinksJob;
use App\Jobs\InitiateCrawlJob;
use App\Jobs\ParseDataJob;
use App\Jobs\ScrapePageJob;
use App\Models\CrawlJob;
use App\Models\PropertyUnit;
use App\Models\Website;
use App\Services\FirecrawlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class CrawlWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_crawl_workflow(): void
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

        $firecrawlMock = Mockery::mock(FirecrawlService::class);

        $firecrawlMock->shouldReceive('scrape')
            ->with('https://www.funda.nl/koop/heel-nederland/', Mockery::any(), Mockery::any(), Mockery::any())
            ->once()
            ->andReturn([
                'markdown' => '<a href="https://www.funda.nl/detail/koop/amsterdam/12345-house/">House</a>',
                'html' => '<a href="https://www.funda.nl/detail/koop/amsterdam/12345-house/">House</a>',
                'links' => ['https://www.funda.nl/detail/koop/amsterdam/12345-house/'],
                'metadata' => ['statusCode' => 200],
            ]);

        $firecrawlMock->shouldReceive('scrape')
            ->with('https://www.funda.nl/huur/heel-nederland/', Mockery::any(), Mockery::any(), Mockery::any())
            ->once()
            ->andReturn([
                'markdown' => '<a href="https://www.funda.nl/detail/huur/rotterdam/67890-apartment/">Apartment</a>',
                'html' => '<a href="https://www.funda.nl/detail/huur/rotterdam/67890-apartment/">Apartment</a>',
                'links' => ['https://www.funda.nl/detail/huur/rotterdam/67890-apartment/'],
                'metadata' => ['statusCode' => 200],
            ]);

        $firecrawlMock->shouldReceive('scrape')
            ->with('https://www.funda.nl/detail/koop/amsterdam/12345-house/', Mockery::any(), Mockery::any(), Mockery::any())
            ->once()
            ->andReturn([
                'markdown' => '<h1>Beautiful House</h1>Kerkstraat 123 1234AB Amsterdam € 450.000 3 kamers 2 slaapkamers',
                'html' => '<h1>Beautiful House</h1>',
                'links' => [],
                'metadata' => ['statusCode' => 200],
            ]);

        $firecrawlMock->shouldReceive('scrape')
            ->with('https://www.funda.nl/detail/huur/rotterdam/67890-apartment/', Mockery::any(), Mockery::any(), Mockery::any())
            ->once()
            ->andReturn([
                'markdown' => '<h1>Modern Apartment</h1>Hoofdweg 456 3000CD Rotterdam € 1500 per maand 2 kamers',
                'html' => '<h1>Modern Apartment</h1>',
                'links' => [],
                'metadata' => ['statusCode' => 200],
            ]);

        $this->app->instance(FirecrawlService::class, $firecrawlMock);

        $initJob = new InitiateCrawlJob($crawlJob->id);
        $initJob->handle();

        foreach ($crawlJob->crawledPages as $page) {
            $scrapeJob = new ScrapePageJob($crawlJob->id, $page->id, $page->url);
            $scrapeJob->handle($firecrawlMock);

            $extractJob = new ExtractLinksJob($crawlJob->id, $page->id);
            $extractJob->handle();

            $parseJob = new ParseDataJob($crawlJob->id, $page->id);
            $parseJob->handle();
        }

        $crawlJob->refresh();
        $newPages = $crawlJob->crawledPages()->whereNotIn('url', [
            'https://www.funda.nl/koop/heel-nederland/',
            'https://www.funda.nl/huur/heel-nederland/',
        ])->get();

        foreach ($newPages as $page) {
            $scrapeJob = new ScrapePageJob($crawlJob->id, $page->id, $page->url);
            $scrapeJob->handle($firecrawlMock);

            $extractJob = new ExtractLinksJob($crawlJob->id, $page->id);
            $extractJob->handle();

            $parseJob = new ParseDataJob($crawlJob->id, $page->id);
            $parseJob->handle();
        }

        $crawlJob->refresh();

        $this->assertEquals('running', $crawlJob->status);
        $this->assertEquals(4, $crawlJob->pages_crawled);
        $this->assertEquals(2, $crawlJob->links_found);

        $this->assertEquals(2, PropertyUnit::count());
        $this->assertEquals(2, $crawlJob->properties_extracted);

        $properties = PropertyUnit::all();
        $this->assertEquals('sale', $properties[0]->transaction_type);
        $this->assertEquals('rent', $properties[1]->transaction_type);
    }
}
