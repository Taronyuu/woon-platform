<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;

class GeneratePropertySlugsCommand extends Command
{
    protected $signature = 'properties:generate-slugs';

    protected $description = 'Generate slugs for all property units';

    public function handle()
    {
        $this->info('Generating slugs for property units...');

        $properties = PropertyUnit::query()->whereNull('slug')->get();

        $this->info("Found {$properties->count()} properties without slugs.");

        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();

        foreach ($properties as $property) {
            $property->slug = $property->generateSlug();
            $property->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('All property slugs have been generated successfully!');

        return Command::SUCCESS;
    }
}
