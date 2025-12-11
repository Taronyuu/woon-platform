<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
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

        $baseUrl = config('app.url');
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create($baseUrl)
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/zoeken')
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/maandlasten')
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/over-oxxen')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/contact')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/privacy')
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        );

        $sitemap->add(
            Url::create($baseUrl . '/voorwaarden')
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        );

        $blogCount = 0;
        BlogPost::query()
            ->published()
            ->select(['slug', 'updated_at'])
            ->orderBy('published_at', 'desc')
            ->chunk(100, function ($posts) use ($sitemap, $baseUrl, &$blogCount) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create($baseUrl . '/blog/' . $post->slug)
                            ->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setLastModificationDate($post->updated_at)
                    );
                    $blogCount++;
                }
            });

        $propertyCount = 0;
        PropertyUnit::query()
            ->select(['slug', 'updated_at', 'images', 'address_street', 'address_number', 'address_city'])
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($properties) use ($sitemap, $baseUrl, &$propertyCount) {
                foreach ($properties as $property) {
                    $url = Url::create($baseUrl . '/woningen/' . $property->slug)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($property->updated_at);

                    if ($property->images && is_array($property->images)) {
                        $images = array_slice($property->images, 0, 5);
                        foreach ($images as $image) {
                            $imageUrl = proxied_image_url($image);
                            if ($imageUrl) {
                                $url->addImage($imageUrl, $property->address_street . ' ' . $property->address_number . ' - ' . $property->address_city);
                            }
                        }
                    }

                    $sitemap->add($url);
                    $propertyCount++;
                }
            });

        $sitemap->writeToFile(public_path('sitemap/sitemap.xml'));

        $this->info("Sitemap generated successfully!");
        $this->info("- Blog posts: {$blogCount}");
        $this->info("- Properties: {$propertyCount}");
        $this->info('Saved to: ' . public_path('sitemap/sitemap.xml'));

        return Command::SUCCESS;
    }
}
