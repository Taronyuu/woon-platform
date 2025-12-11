<?php

namespace App\Console\Commands;

use App\Crawlers\FundaCrawler;
use App\Models\PropertyUnit;
use App\Services\ScrapflyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPropertyStatusCommand extends Command
{
    protected $signature = 'property:check-status
                            {--limit=100 : Number of properties to check}
                            {--concurrency=1 : Number of concurrent requests}
                            {--dry-run : Show what would be updated without making changes}';

    protected $description = 'Check status of random available properties on Funda and update if changed';

    private int $checked = 0;
    private int $updated = 0;
    private int $unchanged = 0;
    private int $failed = 0;

    public function handle(ScrapflyService $scrapfly, FundaCrawler $crawler): int
    {
        $limit = (int) $this->option('limit');
        $concurrency = (int) $this->option('concurrency') ?: 1;
        $dryRun = $this->option('dry-run');

        $properties = DB::table('property_units')
            ->join('property_unit_website', 'property_units.id', '=', 'property_unit_website.property_unit_id')
            ->where('property_units.status', 'available')
            ->where('property_unit_website.website_id', 'funda')
            ->select([
                'property_units.id',
                'property_units.name',
                'property_units.status as current_status',
                'property_unit_website.source_url',
            ])
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        if ($properties->isEmpty()) {
            $this->warn('No available Funda properties found to check.');
            return self::SUCCESS;
        }

        $this->info("Checking status of {$properties->count()} properties...");
        $this->line("Concurrency: {$concurrency}");
        if ($dryRun) {
            $this->warn('Dry run mode - no changes will be made.');
        }
        $this->line('');

        if ($concurrency > 1) {
            $this->processWithConcurrency($properties, $crawler, $concurrency, $dryRun);
        } else {
            $this->processSequentially($properties, $scrapfly, $crawler, $dryRun);
        }

        $this->line('');
        $this->info('Status check complete!');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Checked', $this->checked],
                ['Updated', $this->updated],
                ['Unchanged', $this->unchanged],
                ['Failed', $this->failed],
            ]
        );

        return self::SUCCESS;
    }

    private function processSequentially($properties, ScrapflyService $scrapfly, FundaCrawler $crawler, bool $dryRun): void
    {
        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();

        foreach ($properties as $property) {
            $this->checkProperty($property, $scrapfly, $crawler, $dryRun);
            $bar->advance();
            usleep(500000);
        }

        $bar->finish();
        $this->line('');
    }

    private function processWithConcurrency($properties, FundaCrawler $crawler, int $concurrency, bool $dryRun): void
    {
        $apiKey = config('services.scrapfly.api_key');
        $baseUrl = 'https://api.scrapfly.io/scrape';

        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();

        $chunks = $properties->chunk($concurrency);

        foreach ($chunks as $chunk) {
            $responses = Http::pool(function ($pool) use ($chunk, $apiKey, $baseUrl) {
                foreach ($chunk as $property) {
                    $pool->as($property->id)
                        ->timeout(120)
                        ->get($baseUrl, [
                            'key' => $apiKey,
                            'url' => $property->source_url,
                            'country' => 'nl',
                            'asp' => true,
                            'retry' => false,
                        ]);
                }
            });

            foreach ($chunk as $property) {
                $this->checked++;
                $response = $responses[$property->id] ?? null;

                if (!$response || !$response->successful()) {
                    $this->failed++;
                    $errorMsg = $response ? $response->body() : 'No response';
                    Log::error('[StatusCheck] FAILED to fetch property', [
                        'property_id' => $property->id,
                        'name' => $property->name,
                        'url' => $property->source_url,
                        'error' => $errorMsg,
                    ]);
                    $this->error("  FAILED: #{$property->id} {$property->name} - Could not fetch page");
                    $bar->advance();
                    continue;
                }

                try {
                    $data = $response->json();
                    $html = $data['result']['content'] ?? '';

                    if (empty($html)) {
                        throw new \Exception('Empty HTML content');
                    }

                    $parsedData = $crawler->parseData($html, $property->source_url);
                    $newStatus = $parsedData['status'] ?? 'available';

                    if ($newStatus !== $property->current_status) {
                        if (!$dryRun) {
                            PropertyUnit::query()
                                ->where('id', $property->id)
                                ->update(['status' => $newStatus]);
                        }
                        $this->updated++;

                        Log::info('[StatusCheck] UPDATED property status', [
                            'property_id' => $property->id,
                            'name' => $property->name,
                            'url' => $property->source_url,
                            'old_status' => $property->current_status,
                            'new_status' => $newStatus,
                            'dry_run' => $dryRun,
                        ]);
                        $this->warn("  UPDATED: #{$property->id} {$property->name}: {$property->current_status} → {$newStatus}" . ($dryRun ? ' (dry-run)' : ''));
                    } else {
                        $this->unchanged++;
                        Log::debug('[StatusCheck] UNCHANGED property status', [
                            'property_id' => $property->id,
                            'name' => $property->name,
                            'status' => $property->current_status,
                        ]);
                        $this->line("  UNCHANGED: #{$property->id} {$property->name} - still {$property->current_status}");
                    }
                } catch (\Exception $e) {
                    $this->failed++;
                    Log::error('[StatusCheck] FAILED to parse property', [
                        'property_id' => $property->id,
                        'name' => $property->name,
                        'url' => $property->source_url,
                        'error' => $e->getMessage(),
                    ]);
                    $this->error("  FAILED: #{$property->id} {$property->name} - {$e->getMessage()}");
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->line('');
    }

    private function checkProperty($property, ScrapflyService $scrapfly, FundaCrawler $crawler, bool $dryRun): void
    {
        $this->checked++;

        try {
            $result = $scrapfly->scrape($property->source_url);
            $html = $result['html'];

            $data = $crawler->parseData($html, $property->source_url);
            $newStatus = $data['status'] ?? 'available';

            if ($newStatus !== $property->current_status) {
                if (!$dryRun) {
                    PropertyUnit::query()
                        ->where('id', $property->id)
                        ->update(['status' => $newStatus]);
                }
                $this->updated++;

                Log::info('[StatusCheck] UPDATED property status', [
                    'property_id' => $property->id,
                    'name' => $property->name,
                    'url' => $property->source_url,
                    'old_status' => $property->current_status,
                    'new_status' => $newStatus,
                    'dry_run' => $dryRun,
                ]);
                $this->warn("  UPDATED: #{$property->id} {$property->name}: {$property->current_status} → {$newStatus}" . ($dryRun ? ' (dry-run)' : ''));
            } else {
                $this->unchanged++;
                Log::debug('[StatusCheck] UNCHANGED property status', [
                    'property_id' => $property->id,
                    'name' => $property->name,
                    'status' => $property->current_status,
                ]);
                $this->line("  UNCHANGED: #{$property->id} {$property->name} - still {$property->current_status}");
            }
        } catch (\Exception $e) {
            $this->failed++;
            Log::error('[StatusCheck] FAILED to check property', [
                'property_id' => $property->id,
                'name' => $property->name,
                'url' => $property->source_url,
                'error' => $e->getMessage(),
            ]);
            $this->error("  FAILED: #{$property->id} {$property->name} - {$e->getMessage()}");
        }
    }
}
