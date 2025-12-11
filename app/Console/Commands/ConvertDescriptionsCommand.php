<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ConvertDescriptionsCommand extends Command
{
    protected $signature = 'property:convert-descriptions
                            {--limit= : Limit the number of properties to process}
                            {--concurrency=1 : Number of concurrent requests}
                            {--force : Reconvert even if description already exists}';

    protected $description = 'Convert property descriptions using Mistral AI to markdown format';

    private const API_ENDPOINT = 'https://api.mistral.ai/v1/chat/completions';
    private const API_KEY = 'PeBiaaVvUCCuHu8Seg1zLCOzj0maiZC3';
    private const MODEL = 'mistral-small-2506';

    private int $successCount = 0;
    private int $errorCount = 0;

    public function handle(): int
    {
        $query = PropertyUnit::query()
            ->whereNotNull('original_description')
            ->where('original_description', '!=', '');

        if (!$this->option('force')) {
            $query->whereNull('description');
        }

        if ($limit = $this->option('limit')) {
            $query->limit((int) $limit);
        }

        $properties = $query->get();
        $concurrency = (int) $this->option('concurrency') ?: 1;

        if ($properties->isEmpty()) {
            $this->info('No properties to convert.');
            return Command::SUCCESS;
        }

        $this->info("Converting {$properties->count()} property descriptions...");
        $this->line("Concurrency: {$concurrency}");
        $this->newLine();

        if ($concurrency > 1) {
            $this->processWithConcurrency($properties, $concurrency);
        } else {
            $this->processSequentially($properties);
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        $this->info("Completed: {$this->successCount} converted, {$this->errorCount} failed.");

        return $this->errorCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function processSequentially($properties): void
    {
        foreach ($properties as $index => $property) {
            $current = $index + 1;
            $total = $properties->count();

            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("[{$current}/{$total}] {$property->name} (ID: {$property->id})");
            $this->newLine();

            $this->warn('BEFORE:');
            $this->line(Str::limit($property->original_description, 500));
            $this->newLine();

            try {
                $response = Http::timeout(300)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . self::API_KEY,
                        'Content-Type' => 'application/json',
                    ])
                    ->post(self::API_ENDPOINT, $this->buildRequestBody($property->original_description));

                if (!$response->successful()) {
                    throw new \Exception('API request failed: ' . $response->body());
                }

                $convertedDescription = $response->json()['choices'][0]['message']['content'] ?? '';

                $property->description = $convertedDescription;
                $property->save();

                $this->info('AFTER:');
                $this->line(Str::limit($convertedDescription, 500));
                $this->newLine();

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->error("Failed: {$e->getMessage()}");
            }
        }
    }

    private function processWithConcurrency($properties, int $concurrency): void
    {
        $chunks = $properties->chunk($concurrency);

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->line("Processing batch " . ($chunkIndex + 1) . "/" . $chunks->count() . " (" . $chunk->count() . " properties)");

            $chunkArray = $chunk->values()->all();

            $responses = Http::pool(fn ($pool) =>
                collect($chunkArray)->map(fn ($property) =>
                    $pool->timeout(300)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . self::API_KEY,
                            'Content-Type' => 'application/json',
                        ])
                        ->post(self::API_ENDPOINT, $this->buildRequestBody($property->original_description))
                )->toArray()
            );

            foreach ($responses as $index => $response) {
                $property = $chunkArray[$index];

                try {
                    if (!$response->successful()) {
                        throw new \Exception('API request failed: ' . $response->body());
                    }

                    $convertedDescription = $response->json()['choices'][0]['message']['content'] ?? '';

                    $property->description = $convertedDescription;
                    $property->save();

                    $this->info("  Converted: {$property->name} (ID: {$property->id})");
                    $this->successCount++;

                } catch (\Exception $e) {
                    $this->errorCount++;
                    $this->error("  Failed [{$property->id}]: {$e->getMessage()}");
                }
            }
        }
    }

    private function buildRequestBody(string $originalDescription): array
    {
        $prompt = <<<PROMPT
Je bent een professionele Nederlandse vastgoed copywriter. Herschrijf de volgende woningbeschrijving als vloeiende, goed leesbare tekst.

Structuur:
1. Begin met een pakkende openingsparagraaf (2-3 zinnen) die de woning samenvat en de belangrijkste verkooppunten benoemt
2. Schrijf daarna vloeiende paragrafen over de verschillende ruimtes en kenmerken
3. Sluit af met een paragraaf over de locatie en/of een uitnodiging tot bezichtiging

Stijlregels:
- Schrijf in vloeiende, verbonden zinnen en paragrafen - GEEN opsommingen of bullet points
- Gebruik **bold** alleen voor de allerbelangrijkste kenmerken (max 5-6 per tekst)
- Gebruik kopjes (##) alleen om grote secties te scheiden indien de tekst lang is
- Schrijf warm, uitnodigend en professioneel Nederlands
- Laat de tekst natuurlijk lezen alsof een makelaar de woning persoonlijk beschrijft
- Combineer technische details in vloeiende zinnen, niet als losse feiten

Inhoudelijk:
- Behoud ALLE feitelijke informatie exact zoals gegeven
- Voeg GEEN informatie toe die niet in de originele tekst staat
- Geef ALLEEN de herschreven tekst terug, geen uitleg of opmerkingen

Originele beschrijving:
{$originalDescription}
PROMPT;

        return [
            'model' => self::MODEL,
            'max_tokens' => 8192,
            'temperature' => 0.4,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ];
    }
}
