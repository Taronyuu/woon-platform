<?php

namespace App\Console\Commands;

use App\Models\PropertyUnit;
use Illuminate\Console\Command;

class RemoveDuplicateFloorPlansCommand extends Command
{
    protected $signature = 'clean:duplicate-floorplans';

    protected $description = 'Remove floor plans that are already in the images array';

    public function handle()
    {
        $this->info('Removing duplicate floor plans...');

        $properties = PropertyUnit::query()
            ->whereNotNull('images')
            ->whereNotNull('floor_plans')
            ->get();

        $cleaned = 0;

        foreach ($properties as $property) {
            $images = $property->images;
            $floorPlans = $property->floor_plans;

            if (!is_array($images) || !is_array($floorPlans) || empty($floorPlans)) {
                continue;
            }

            $imageBaseUrls = [];
            foreach ($images as $imageUrl) {
                $baseUrl = preg_replace('/\?.*$/', '', $imageUrl);
                $imageBaseUrls[$baseUrl] = true;
            }

            $originalCount = count($floorPlans);
            $filteredPlans = [];

            foreach ($floorPlans as $planUrl) {
                $basePlanUrl = preg_replace('/\?.*$/', '', $planUrl);
                if (!isset($imageBaseUrls[$basePlanUrl])) {
                    $filteredPlans[] = $planUrl;
                }
            }

            if (count($filteredPlans) < $originalCount) {
                $property->floor_plans = !empty($filteredPlans) ? array_values($filteredPlans) : null;
                $property->save();
                $cleaned++;
                $removed = $originalCount - count($filteredPlans);
                $this->info("Cleaned {$property->slug}: removed {$removed} duplicate floor plan(s)");
            }
        }

        $this->info("Done! Cleaned {$cleaned} properties.");

        return 0;
    }
}
