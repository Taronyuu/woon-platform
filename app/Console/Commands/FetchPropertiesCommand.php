<?php

namespace App\Console\Commands;

use App\Models\DiscoveredUrl;
use App\Models\PropertyUnit;
use App\Services\ScrapflyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchPropertiesCommand extends Command
{
    protected $signature = 'crawl:fetch
                            {website : The website ID to fetch properties for}
                            {--limit= : Maximum number of URLs to process}
                            {--retry-failed : Also retry failed URLs}';

    protected $description = 'Fetch and parse property data from discovered URLs';

    public function handle(ScrapflyService $scrapfly): int
    {
        $websiteId = $this->argument('website');
        $config = config("websites.{$websiteId}");
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
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
        $this->line('');

        $processed = 0;
        $properties = 0;
        $deleted = 0;
        $failed = 0;

        foreach ($urls as $discoveredUrl) {
            $url = $discoveredUrl->url;
            $this->line("Fetching: {$url}");

            try {
                $data = $scrapfly->scrape($url);
                $statusCode = $data['status_code'] ?? 200;

                if ($statusCode === 404) {
                    $discoveredUrl->delete();
                    $deleted++;
                    $this->warn("  Deleted (404)");
                    $processed++;
                    continue;
                }

                if (!$isLeafPage($url)) {
                    $discoveredUrl->delete();
                    $deleted++;
                    $this->warn("  Deleted (not leaf page)");
                    $processed++;
                    continue;
                }

                $propertyData = $parseData($data['html'], $url);

                if (empty($propertyData)) {
                    $discoveredUrl->delete();
                    $deleted++;
                    $this->warn("  Deleted (no data extracted)");
                    $processed++;
                    continue;
                }

                $this->createOrUpdateProperty($propertyData, $websiteId, $url);
                $discoveredUrl->markAsFetched();
                $properties++;
                $this->info("  Property saved");

            } catch (\Exception $e) {
                $discoveredUrl->markAsFailed($e->getMessage());
                $failed++;
                $this->error("  Failed: {$e->getMessage()}");
            }

            $processed++;
            usleep($delayMs * 1000);
        }

        $this->line('');
        $this->info('Fetch complete!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['URLs Processed', $processed],
                ['Properties Created/Updated', $properties],
                ['URLs Deleted (invalid)', $deleted],
                ['URLs Failed', $failed],
            ]
        );

        return self::SUCCESS;
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
