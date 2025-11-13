<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CleanTrackingPixelsCommand extends Command
{
    protected $signature = 'clean:tracking-pixels';

    protected $description = 'Remove tracking pixels and analytics URLs from property images';

    public function handle()
    {
        $this->info('Cleaning tracking pixels from property images...');

        $trackingPatterns = ['pixel', 'akam', 'tracking', 'analytics', 'beacon', 'marketinsightsassets'];

        $properties = PropertyUnit::query()->whereNotNull('images')->get();
        $cleaned = 0;

        foreach ($properties as $property) {
            $images = $property->images;
            if (!is_array($images)) {
                continue;
            }

            $originalCount = count($images);
            $cleanedImages = array_filter($images, function ($url) use ($trackingPatterns) {
                foreach ($trackingPatterns as $pattern) {
                    if (Str::contains($url, $pattern)) {
                        return false;
                    }
                }
                return true;
            });

            if (count($cleanedImages) < $originalCount) {
                $property->images = array_values($cleanedImages);
                $property->save();
                $cleaned++;
                $this->info("Cleaned {$property->slug}: removed " . ($originalCount - count($cleanedImages)) . " tracking URL(s)");
            }
        }

        $this->info("Done! Cleaned {$cleaned} properties.");

        return 0;
    }
}
