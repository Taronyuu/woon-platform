<?php

namespace Tests\Feature;

use App\Services\FirecrawlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FirecrawlServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_scrape_returns_data(): void
    {
        Http::fake([
            '*/scrape' => Http::response([
                'success' => true,
                'data' => [
                    'markdown' => '# Test Content',
                    'html' => '<h1>Test Content</h1>',
                    'links' => ['https://example.com/link1'],
                    'metadata' => ['title' => 'Test Page'],
                ],
            ], 200),
        ]);

        config(['services.firecrawl.base_url' => 'https://api.example.com']);
        config(['services.firecrawl.username' => 'test_user']);
        config(['services.firecrawl.password' => 'test_pass']);

        $service = new FirecrawlService();
        $result = $service->scrape('https://example.com');

        $this->assertEquals('# Test Content', $result['markdown']);
        $this->assertEquals('<h1>Test Content</h1>', $result['html']);
        $this->assertCount(1, $result['links']);
    }

    public function test_failed_scrape_throws_exception_on_http_error(): void
    {
        Http::fake([
            '*/scrape' => Http::response([], 500),
        ]);

        config(['services.firecrawl.base_url' => 'https://api.example.com']);
        config(['services.firecrawl.username' => 'test_user']);
        config(['services.firecrawl.password' => 'test_pass']);

        $service = new FirecrawlService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Firecrawl API error: 500');

        $service->scrape('https://example.com');
    }

    public function test_failed_scrape_throws_exception_on_unsuccessful_response(): void
    {
        Http::fake([
            '*/scrape' => Http::response([
                'success' => false,
                'error' => 'Page not found',
            ], 200),
        ]);

        config(['services.firecrawl.base_url' => 'https://api.example.com']);
        config(['services.firecrawl.username' => 'test_user']);
        config(['services.firecrawl.password' => 'test_pass']);

        $service = new FirecrawlService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Firecrawl scrape failed: Page not found');

        $service->scrape('https://example.com');
    }

    public function test_scrape_uses_authentication(): void
    {
        Http::fake([
            '*/scrape' => Http::response([
                'success' => true,
                'data' => ['markdown' => 'test'],
            ], 200),
        ]);

        config(['services.firecrawl.base_url' => 'https://api.example.com']);
        config(['services.firecrawl.username' => 'test_user']);
        config(['services.firecrawl.password' => 'test_pass']);

        $service = new FirecrawlService();
        $service->scrape('https://example.com');

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization');
        });
    }
}
