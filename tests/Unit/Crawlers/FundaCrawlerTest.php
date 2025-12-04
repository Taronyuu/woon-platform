<?php

namespace Tests\Unit\Crawlers;

use App\Crawlers\FundaCrawler;
use App\Crawlers\WebsiteCrawler;
use Tests\TestCase;

class FundaCrawlerTest extends TestCase
{
    private WebsiteCrawler $crawler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crawler = new WebsiteCrawler('funda');
    }

    public function test_get_start_urls_returns_correct_urls(): void
    {
        $urls = $this->crawler->getStartUrls();

        $this->assertNotEmpty($urls);
        $this->assertContains('https://www.funda.nl/zoeken/koop/?page=1', $urls);
        $this->assertContains('https://www.funda.nl/zoeken/huur/?page=1', $urls);
    }

    public function test_should_crawl_allows_funda_detail_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/detail/koop/amsterdam/huis-straat-1/12345/');

        $this->assertTrue($result);
    }

    public function test_should_crawl_allows_funda_rent_detail_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/detail/huur/utrecht/appartement-weg-5/67890/');

        $this->assertTrue($result);
    }

    public function test_should_crawl_denies_non_funda_urls(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.example.com/');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_search_pages(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/zoeken/koop/?page=1');

        $this->assertFalse($result);
    }

    public function test_should_crawl_denies_detail_sub_pages(): void
    {
        $result = $this->crawler->shouldCrawl('https://www.funda.nl/detail/koop/amsterdam/huis-straat-1/12345/media/');

        $this->assertFalse($result);
    }

    public function test_is_leaf_page_returns_true_for_detail_pages(): void
    {
        $result = $this->crawler->isLeafPage('https://www.funda.nl/detail/koop/amsterdam/huis-straat-1/12345/');

        $this->assertTrue($result);
    }

    public function test_is_leaf_page_returns_false_for_search_pages(): void
    {
        $result = $this->crawler->isLeafPage('https://www.funda.nl/zoeken/koop/?page=1');

        $this->assertFalse($result);
    }

    public function test_parse_data_returns_empty_for_non_detail_pages(): void
    {
        $fundaCrawler = app(FundaCrawler::class);
        $content = '<h1>Search Results</h1>';
        $url = 'https://www.funda.nl/zoeken/koop/';

        $result = $fundaCrawler->parseData($content, $url);

        $this->assertEmpty($result);
    }

    public function test_parse_data_returns_data_for_detail_pages(): void
    {
        $fundaCrawler = app(FundaCrawler::class);
        $content = '<h1>Beautiful House</h1><div class="description">A great property</div>€ 450.000 3 kamers 2 slaapkamers';
        $url = 'https://www.funda.nl/detail/koop/amsterdam/huis-straat-1/12345-beautiful-house/';

        $result = $fundaCrawler->parseData($content, $url);

        $this->assertNotEmpty($result);
        $this->assertEquals('sale', $result['transaction_type']);
    }

    public function test_parse_data_identifies_rent_transaction(): void
    {
        $fundaCrawler = app(FundaCrawler::class);
        $content = '<h1>Apartment for Rent</h1>';
        $url = 'https://www.funda.nl/detail/huur/amsterdam/appartement-weg-5/67890-apartment/';

        $result = $fundaCrawler->parseData($content, $url);

        $this->assertEquals('rent', $result['transaction_type']);
    }
}
