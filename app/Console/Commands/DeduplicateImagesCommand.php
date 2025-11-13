<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;

class DeduplicateImagesCommand extends Command
{
    protected $signature = 'clean:deduplicate-images';

    protected $description = 'Remove duplicate images (same URL without query parameters)';

    public function handle()
    {
        $this->info('Deduplicating images...');

        $properties = PropertyUnit::query()->whereNotNull('images')->get();
        $cleaned = 0;

        foreach ($properties as $property) {
            $images = $property->images;
            if (!is_array($images) || count($images) === 0) {
                continue;
            }

            $originalCount = count($images);
            $uniqueImages = [];
            $seenBaseUrls = [];

            foreach ($images as $url) {
                $baseUrl = preg_replace('/\?.*$/', '', $url);

                if (!isset($seenBaseUrls[$baseUrl])) {
                    $uniqueImages[] = $url;
                    $seenBaseUrls[$baseUrl] = true;
                }
            }

            if (count($uniqueImages) < $originalCount) {
                $property->images = array_values($uniqueImages);
                $property->save();
                $cleaned++;
                $removed = $originalCount - count($uniqueImages);
                $this->info("Cleaned {$property->slug}: removed {$removed} duplicate(s)");
            }
        }

        $this->info("Done! Cleaned {$cleaned} properties.");

        return 0;
    }
}
