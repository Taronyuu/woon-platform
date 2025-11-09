<?php

namespace Tests\Unit\Crawlers;

use App\Crawlers\FundaCrawler;
use App\Models\CrawlJob;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundaCrawlerTest extends TestCase
{
    use RefreshDatabase;

    private FundaCrawler $crawler;
    private CrawlJob $crawlJob;

    protected function setUp(): void
    {
        parent::setUp();

        $website = Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/'],
        ]);

        $this->crawlJob = CrawlJob::create([
            'website_id' => $website->id,
            'status' => 'pending',
        ]);

        $this->crawler = new FundaCrawler($this->crawlJob);
    }

    public function test_get_start_urls_returns_correct_urls(): void
    {
        $urls = $this->crawler->getStartUrls();

        $this->assertCount(2, $urls);
        $this->assertContains('https://www.funda.nl/koop/heel-nederland/', $urls);
        $this->assertContains('https://www.funda.nl/huur/heel-nederland/', $urls);
    }

    public function test_should_crawl_allows_funda_sale_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/koop/amsterdam/');

        $this->assertTrue($result);
    }

    public function test_should_crawl_allows_funda_rent_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/huur/utrecht/');

        $this->assertTrue($result);
    }

    public function test_should_crawl_allows_funda_detail_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/detail/12345-street-name/');

        $this->assertTrue($result);
    }

    public function test_should_crawl_denies_non_funda_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.example.com/');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_hypotheek_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/hypotheek/');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_nieuwbouw_service_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/nieuwbouw-service/');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_mijn_funda_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/mijn-funda/');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_urls_without_koop_huur_detail(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/about/');

        $this->assertFalse($result);
    }

    public function test_parse_data_returns_empty_for_non_detail_pages(): void
    {
        $content = '<h1>Search Results</h1>';
        $url = 'https://www.funda.nl/koop/amsterdam/';

        $result = $this->crawler->parseData($content, $url);

        $this->assertEmpty($result);
    }

    public function test_parse_data_returns_data_for_detail_pages(): void
    {
        $content = '<h1>Beautiful House</h1><div class="description">A great property</div>€ 450.000 3 kamers 2 slaapkamers';
        $url = 'https://www.funda.nl/detail/koop/amsterdam/12345-street-name/';

        $result = $this->crawler->parseData($content, $url);

        $this->assertNotEmpty($result);
        $this->assertEquals('sale', $result['transaction_type']);
        $this->assertEquals('12345', $result['external_id']);
    }

    public function test_parse_data_identifies_rent_transaction(): void
    {
        $content = '<h1>Apartment for Rent</h1>';
        $url = 'https://www.funda.nl/detail/huur/amsterdam/67890-street/';

        $result = $this->crawler->parseData($content, $url);

        $this->assertEquals('rent', $result['transaction_type']);
        $this->assertEquals('67890', $result['external_id']);
    }
}
