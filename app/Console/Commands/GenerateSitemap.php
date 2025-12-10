<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.xml file';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create('/zoeken')
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create('/maandlasten')
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create('/over-oxxen')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create('/contact')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create('/privacy')
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        );

        $sitemap->add(
            Url::create('/voorwaarden')
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        );

        $propertyCount = 0;
        PropertyUnit::query()
            ->select(['slug', 'updated_at'])
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($properties) use ($sitemap, &$propertyCount) {
                foreach ($properties as $property) {
                    $sitemap->add(
                        Url::create("/woningen/{$property->slug}")
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($property->updated_at)
                    );
                    $propertyCount++;
                }
            });

        $sitemap->writeToFile(public_path('sitemap/sitemap.xml'));

        $this->info("Sitemap generated successfully with {$propertyCount} properties!");
        $this->info('Saved to: ' . public_path('sitemap.xml'));

        return Command::SUCCESS;
    }
}
