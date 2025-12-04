<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use App\Services\MistralService;
use Illuminate\Console\Command;

class ConvertDescriptionsCommand extends Command
{
    protected $signature = 'property:convert-descriptions
                            {--limit= : Limit the number of properties to process}
                            {--force : Reconvert even if description already exists}';

    protected $description = 'Convert property descriptions using Mistral AI to markdown format';

    public function handle(MistralService $mistral): int
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

        if ($properties->isEmpty()) {
            $this->info('No properties to convert.');
            return Command::SUCCESS;
        }

        $this->info("Converting {$properties->count()} property descriptions...");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;

        foreach ($properties as $index => $property) {
            $current = $index + 1;
            $total = $properties->count();

            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("[{$current}/{$total}] {$property->name} (ID: {$property->id})");
            $this->newLine();

            $this->warn('BEFORE:');
            $this->line(\Illuminate\Support\Str::limit($property->original_description, 500));
            $this->newLine();

            try {
                $convertedDescription = $this->convertDescription($mistral, $property->original_description);

                $property->description = $convertedDescription;
                $property->save();

                $this->info('AFTER:');
                $this->line(\Illuminate\Support\Str::limit($convertedDescription, 500));
                $this->newLine();

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Failed: {$e->getMessage()}");
            }
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        $this->info("Completed: {$successCount} converted, {$errorCount} failed.");

        return $errorCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function convertDescription(MistralService $mistral, string $originalDescription): string
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

        return $mistral->sendPrompt($prompt);
    }
}
