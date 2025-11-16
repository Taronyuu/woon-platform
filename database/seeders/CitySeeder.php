<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = resource_path('data/dutch_cities.json');
        $cities = json_decode(file_get_contents($jsonPath), true);

        foreach ($cities as $cityData) {
            City::query()->updateOrCreate(
                ['slug' => Str::slug($cityData['name'])],
                [
                    'name' => $cityData['name'],
                    'province' => $cityData['state_name'],
                ]
            );
        }
    }
}
