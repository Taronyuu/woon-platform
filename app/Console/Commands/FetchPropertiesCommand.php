<?php

namespace App\Console\Commands;

use App\Models\DiscoveredUrl;
use App\Models\PropertyUnit;
use App\Services\ScrapflyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchPropertiesCommand extends Command
{
    protected $signature = 'crawl:fetch
                            {website : The website ID to fetch properties for}
                            {--limit= : Maximum number of URLs to process}
                            {--concurrency=1 : Number of concurrent requests}
                            {--retry-failed : Also retry failed URLs}';

    protected $description = 'Fetch and parse property data from discovered URLs';

    private int $processed = 0;
    private int $properties = 0;
    private int $deleted = 0;
    private int $failed = 0;

    public function handle(ScrapflyService $scrapfly): int
    {
        $websiteId = $this->argument('website');
        $config = config("websites.{$websiteId}");
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $concurrency = (int) $this->option('concurrency') ?: 1;
        $retryFailed = $this->option('retry-failed');

        if (!$config) {
            $this->error("Website not found: {$websiteId}");
            return self::FAILURE;
        }

        $isLeafPage = $config['is_leaf_page'] ?? fn() => true;
        $parseData = $config['parse_data'] ?? fn() => [];
        $delayMs = $config['delay_ms'] ?? 1000;

        $query = DiscoveredUrl::query()
            ->forWebsite($websiteId)
            ->where(function ($q) use ($retryFailed) {
                $q->where('status', 'pending');
                if ($retryFailed) {
                    $q->orWhere('status', 'failed');
                }
            })
            ->orderBy('discovered_at', 'asc');

        if ($limit) {
            $query->limit($limit);
        }

        $urls = $query->get();

        if ($urls->isEmpty()) {
            $this->info('No URLs to process.');
            return self::SUCCESS;
        }

        $this->info("Fetching properties for: {$config['name']}");
        $this->line("URLs to process: {$urls->count()}");
        $this->line("Concurrency: {$concurrency}");
        $this->line('');

        if ($concurrency > 1) {
            $this->processWithConcurrency($urls, $websiteId, $isLeafPage, $parseData, $concurrency);
        } else {
            $this->processSequentially($urls, $websiteId, $scrapfly, $isLeafPage, $parseData, $delayMs);
        }

        $this->line('');
        $this->info('Fetch complete!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['URLs Processed', $this->processed],
                ['Properties Created/Updated', $this->properties],
                ['URLs Deleted (invalid)', $this->deleted],
                ['URLs Failed', $this->failed],
            ]
        );

        return self::SUCCESS;
    }

    protected function processSequentially($urls, string $websiteId, ScrapflyService $scrapfly, callable $isLeafPage, callable $parseData, int $delayMs): void
    {
        foreach ($urls as $discoveredUrl) {
            $url = $discoveredUrl->url;
            $this->line("Fetching: {$url}");

            try {
                $data = $scrapfly->scrape($url);
                $this->processUrl($discoveredUrl, $data['html'], $data['status_code'] ?? 200, $websiteId, $isLeafPage, $parseData);
            } catch (\Exception $e) {
                $discoveredUrl->markAsFailed($e->getMessage());
                $this->failed++;
                $this->error("  Failed: {$e->getMessage()}");
            }

            $this->processed++;
            usleep($delayMs * 1000);
        }
    }

    protected function processWithConcurrency($urls, string $websiteId, callable $isLeafPage, callable $parseData, int $concurrency): void
    {
        $chunks = $urls->chunk($concurrency);
        $apiKey = config('services.scrapfly.api_key');
        $baseUrl = 'https://api.scrapfly.io/scrape';

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->line("Processing batch " . ($chunkIndex + 1) . "/" . $chunks->count() . " (" . $chunk->count() . " URLs)");

            $chunkArray = $chunk->values()->all();

            $responses = Http::pool(fn ($pool) =>
                collect($chunkArray)->map(fn ($discoveredUrl) =>
                    $pool->timeout(120)->get($baseUrl, [
                        'key' => $apiKey,
                        'url' => $discoveredUrl->url,
                        'country' => 'nl',
                        'asp' => 'true',
                    ])
                )->toArray()
            );

            foreach ($responses as $index => $response) {
                $discoveredUrl = $chunkArray[$index];

                try {
                    if (!$response->successful()) {
                        $discoveredUrl->markAsFailed("HTTP {$response->status()}");
                        $this->failed++;
                        $this->error("  Failed [{$discoveredUrl->url}]: HTTP {$response->status()}");
                        $this->processed++;
                        continue;
                    }

                    $data = $response->json();
                    if (!isset($data['result']['content'])) {
                        $discoveredUrl->markAsFailed("No content in response");
                        $this->failed++;
                        $this->error("  Failed [{$discoveredUrl->url}]: No content");
                        $this->processed++;
                        continue;
                    }

                    $html = $data['result']['content'];
                    $statusCode = $data['result']['status_code'] ?? 200;

                    $this->processUrl($discoveredUrl, $html, $statusCode, $websiteId, $isLeafPage, $parseData);

                } catch (\Exception $e) {
                    $discoveredUrl->markAsFailed($e->getMessage());
                    $this->failed++;
                    $this->error("  Failed [{$discoveredUrl->url}]: {$e->getMessage()}");
                }

                $this->processed++;
            }
        }
    }

    protected function processUrl(DiscoveredUrl $discoveredUrl, string $html, int $statusCode, string $websiteId, callable $isLeafPage, callable $parseData): void
    {
        $url = $discoveredUrl->url;

        if ($statusCode === 404) {
            $discoveredUrl->delete();
            $this->deleted++;
            $this->warn("  Deleted (404): {$url}");
            return;
        }

        if (!$isLeafPage($url)) {
            $discoveredUrl->delete();
            $this->deleted++;
            $this->warn("  Deleted (not leaf page): {$url}");
            return;
        }

        $propertyData = $parseData($html, $url);

        if (empty($propertyData)) {
            $discoveredUrl->delete();
            $this->deleted++;
            $this->warn("  Deleted (no data extracted): {$url}");
            return;
        }

        $this->createOrUpdateProperty($propertyData, $websiteId, $url);
        $discoveredUrl->markAsFetched();
        $this->properties++;
        $this->info("  Property saved: {$url}");
    }

    protected function createOrUpdateProperty(array $data, string $websiteId, string $url): void
    {
        $externalId = $data['external_id'] ?? null;
        unset($data['external_id']);

        $sourceUrl = $data['source_url'] ?? $url;
        unset($data['source_url']);

        $propertyUnit = PropertyUnit::updateOrCreate(
            [
                'address_postal_code' => $data['address_postal_code'] ?? null,
                'address_number' => $data['address_number'] ?? null,
                'address_addition' => $data['address_addition'] ?? null,
            ],
            $data
        );

        $existingPivot = DB::table('property_unit_website')
            ->where('property_unit_id', $propertyUnit->id)
            ->where('website_id', $websiteId)
            ->first();

        if ($existingPivot) {
            DB::table('property_unit_website')
                ->where('id', $existingPivot->id)
                ->update([
                    'external_id' => $externalId,
                    'source_url' => $sourceUrl,
                    'last_seen_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('property_unit_website')->insert([
                'property_unit_id' => $propertyUnit->id,
                'website_id' => $websiteId,
                'external_id' => $externalId,
                'source_url' => $sourceUrl,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
