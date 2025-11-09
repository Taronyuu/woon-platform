<?php

namespace Tests\Feature;

use App\Models\CrawlJob;
use App\Models\CrawledPage;
use App\Models\PropertyUnit;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FullCrawlDemonstrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_funda_crawl_with_property_extraction(): void
    {
        Http::fake([
            '*funda.nl/koop/heel-nederland*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => $this->getListPageContent(),
                    'html' => '<html>...</html>',
                    'links' => [
                        'https://www.funda.nl/detail/koop/amsterdam/huis-hoofdstraat-123/12345678/',
                        'https://www.funda.nl/detail/koop/utrecht/appartement-parkweg-456/87654321/',
                        'https://www.funda.nl/koop/amsterdam/',
                    ],
                    'metadata' => ['statusCode' => 200],
                ],
            ], 200),
            '*detail/koop/amsterdam*12345678*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => $this->getAmsterdamPropertyContent(),
                    'html' => '<html>...</html>',
                    'links' => [],
                    'metadata' => ['statusCode' => 200],
                ],
            ], 200),
            '*detail/koop/utrecht*87654321*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => $this->getUtrechtPropertyContent(),
                    'html' => '<html>...</html>',
                    'links' => [],
                    'metadata' => ['statusCode' => 200],
                ],
            ], 200),
            '*koop/amsterdam*' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => 'Amsterdam properties...',
                    'html' => '<html>...</html>',
                    'links' => [],
                    'metadata' => ['statusCode' => 200],
                ],
            ], 200),
        ]);

        $website = Website::create([
            'name' => 'Funda Test',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 2,
            'delay_ms' => 0,
            'use_flaresolverr' => true,
            'start_urls' => ['https://www.funda.nl/koop/heel-nederland/'],
            'is_active' => true,
        ]);

        $this->artisan('crawl:website', ['website' => $website->id])
            ->expectsOutput('Running in SYNC mode - this will block until complete')
            ->assertSuccessful();

        $crawlJob = CrawlJob::latest()->first();

        $this->assertEquals(4, $crawlJob->pages_crawled);
        $this->assertEquals(2, $crawlJob->properties_extracted);
        $this->assertGreaterThan(0, $crawlJob->links_found);

        $properties = PropertyUnit::all();
        $this->assertEquals(2, $properties->count());

        $amsterdamProperty = PropertyUnit::where('address_city', 'Amsterdam')->first();
        $this->assertNotNull($amsterdamProperty);
        $this->assertEquals('Prachtig herenhuis in Amsterdam Centrum', $amsterdamProperty->title);
        $this->assertEquals('sale', $amsterdamProperty->transaction_type);
        $this->assertEquals(875000, $amsterdamProperty->buyprice);
        $this->assertEquals('Hoofdstraat', $amsterdamProperty->address_street);
        $this->assertEquals('1012AB', $amsterdamProperty->address_postal_code);
        $this->assertEquals(150, $amsterdamProperty->surface);
        $this->assertEquals(200, $amsterdamProperty->lotsize);
        $this->assertEquals(5, $amsterdamProperty->bedrooms);
        $this->assertEquals(3, $amsterdamProperty->sleepingrooms);
        $this->assertEquals(2, $amsterdamProperty->bathrooms);
        $this->assertEquals(1920, $amsterdamProperty->construction_year);
        $this->assertEquals('B', $amsterdamProperty->energy_label);

        $utrechtProperty = PropertyUnit::where('address_city', 'Utrecht')->first();
        $this->assertNotNull($utrechtProperty);
        $this->assertEquals('Modern appartement met dakterras', $utrechtProperty->title);
        $this->assertEquals('sale', $amsterdamProperty->transaction_type);
        $this->assertEquals(425000, $utrechtProperty->buyprice);
        $this->assertEquals('Parkweg', $utrechtProperty->address_street);
        $this->assertEquals('3511AA', $utrechtProperty->address_postal_code);
        $this->assertEquals(85, $utrechtProperty->surface);
        $this->assertEquals(3, $utrechtProperty->bedrooms);
        $this->assertEquals(2, $utrechtProperty->sleepingrooms);
        $this->assertEquals(1, $utrechtProperty->bathrooms);
        $this->assertEquals(2018, $utrechtProperty->construction_year);
        $this->assertEquals('A', $utrechtProperty->energy_label);

        $this->assertEquals(1, $amsterdamProperty->websites()->count());
        $this->assertEquals(1, $utrechtProperty->websites()->count());
    }

    private function getListPageContent(): string
    {
        return <<<'MD'
# Koopwoningen in Nederland

Bekijk alle koopwoningen in Nederland.

## Aanbod

[Prachtig herenhuis in Amsterdam](/detail/koop/amsterdam/huis-hoofdstraat-123/12345678/)
[Modern appartement in Utrecht](/detail/koop/utrecht/appartement-parkweg-456/87654321/)
MD;
    }

    private function getAmsterdamPropertyContent(): string
    {
        return <<<'MD'
# Prachtig herenhuis in Amsterdam Centrum

€ 875.000 k.k.

Hoofdstraat 123
1012AB Amsterdam

## Kenmerken

- Woonoppervlakte: 150 m²
- Perceeloppervlakte: 200 m²
- Aantal kamers: 5
- Aantal slaapkamers: 3
- Aantal badkamers: 2
- Bouwjaar: 1920
- Energielabel: B

## Omschrijving

Dit prachtige herenhuis ligt in het hart van Amsterdam Centrum. De woning beschikt over een ruime woonkamer, moderne keuken, en een zonnige tuin op het zuiden.

**Makelaar:** VBO Makelaars Amsterdam
MD;
    }

    private function getUtrechtPropertyContent(): string
    {
        return <<<'MD'
# Modern appartement met dakterras

€ 425.000 k.k.

Parkweg 456
3511AA Utrecht

## Kenmerken

- Woonoppervlakte: 85 m²
- Aantal kamers: 3
- Aantal slaapkamers: 2
- Aantal badkamers: 1
- Bouwjaar: 2018
- Energielabel: A

## Omschrijving

Modern appartement met ruim dakterras. De woning is recent gebouwd en beschikt over hoogwaardige afwerking en energiezuinige voorzieningen.

**Makelaar:** Makelaardij Utrecht Centrum
MD;
    }
}
