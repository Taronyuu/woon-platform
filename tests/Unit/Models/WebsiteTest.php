<?php

namespace Tests\Unit\Models;

use App\Models\CrawlJob;
use App\Models\PropertyUnit;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_units_relationship(): void
    {
        $website = Website::create([
            'name' => 'Test Website',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com'],
        ]);

        $propertyUnit = PropertyUnit::create([
            'title' => 'Test Property',
            'property_type' => 'house',
            'transaction_type' => 'sale',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $website->propertyUnits()->attach($propertyUnit->id, [
            'external_id' => '12345',
            'source_url' => 'https://example.com/property/12345',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $this->assertCount(1, $website->propertyUnits);
        $this->assertEquals('Test Property', $website->propertyUnits->first()->title);
        $this->assertEquals('12345', $website->propertyUnits->first()->pivot->external_id);
    }

    public function test_crawl_jobs_relationship(): void
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

        $this->assertCount(1, $website->crawlJobs);
        $this->assertEquals('pending', $website->crawlJobs->first()->status);
    }

    public function test_fillable_fields(): void
    {
        $website = new Website();

        $fillable = $website->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('base_url', $fillable);
        $this->assertContains('crawler_class', $fillable);
        $this->assertContains('max_depth', $fillable);
        $this->assertContains('delay_ms', $fillable);
        $this->assertContains('use_flaresolverr', $fillable);
        $this->assertContains('start_urls', $fillable);
        $this->assertContains('is_active', $fillable);
    }

    public function test_casts(): void
    {
        $website = Website::create([
            'name' => 'Test',
            'base_url' => 'https://example.com',
            'crawler_class' => 'App\Crawlers\FundaCrawler',
            'start_urls' => ['https://example.com/1', 'https://example.com/2'],
            'use_flaresolverr' => true,
            'is_active' => false,
        ]);

        $this->assertIsArray($website->start_urls);
        $this->assertIsBool($website->use_flaresolverr);
        $this->assertIsBool($website->is_active);
        $this->assertTrue($website->use_flaresolverr);
        $this->assertFalse($website->is_active);
    }
}
