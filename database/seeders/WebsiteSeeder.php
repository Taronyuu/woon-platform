<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        Website::create([
            'name' => 'Funda',
            'base_url' => 'https://www.funda.nl',
            'crawler_class' => 'App\\Crawlers\\FundaCrawler',
            'max_depth' => 3,
            'delay_ms' => 1000,
            'use_flaresolverr' => true,
            'start_urls' => [
                'https://www.funda.nl/koop/heel-nederland/',
                'https://www.funda.nl/huur/heel-nederland/',
            ],
            'is_active' => true,
        ]);
    }
}
