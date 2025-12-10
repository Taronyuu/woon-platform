<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $propertiesWithoutPivot = DB::table('property_units')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('property_unit_website')
                    ->whereColumn('property_unit_website.property_unit_id', 'property_units.id');
            })
            ->get();

        foreach ($propertiesWithoutPivot as $property) {
            $crawledPage = $this->findMatchingCrawledPage($property);

            if ($crawledPage) {
                DB::table('property_unit_website')->insert([
                    'property_unit_id' => $property->id,
                    'website_id' => 'funda',
                    'external_id' => $this->extractExternalId($crawledPage->url),
                    'source_url' => $crawledPage->url,
                    'first_seen_at' => $property->first_seen_at ?? now(),
                    'last_seen_at' => $property->last_seen_at ?? now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
    }

    private function findMatchingCrawledPage(object $property): ?object
    {
        $street = strtolower($property->address_street ?? '');
        $city = strtolower($property->address_city ?? '');

        if (empty($street) || empty($city)) {
            return null;
        }

        $slugStreet = str_replace(' ', '-', $street);

        return DB::table('crawled_pages')
            ->where('url', 'LIKE', '%/detail/%')
            ->where('url', 'LIKE', "%/{$city}/%")
            ->where('url', 'LIKE', "%{$slugStreet}%")
            ->whereNotNull('raw_html')
            ->orderBy('id', 'desc')
            ->first();
    }

    private function extractExternalId(string $url): ?string
    {
        if (preg_match('/\/(\d+)\/?$/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
};
