<?php

namespace Tests\Unit\Models;

use App\Models\PropertyUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_fields(): void
    {
        $propertyUnit = new PropertyUnit();

        $fillable = $propertyUnit->getFillable();

        $this->assertContains('title', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('property_type', $fillable);
        $this->assertContains('buyprice', $fillable);
        $this->assertContains('address_street', $fillable);
        $this->assertContains('bedrooms', $fillable);
        $this->assertContains('images', $fillable);
    }

    public function test_casts_json_fields(): void
    {
        $propertyUnit = PropertyUnit::create([
            'title' => 'Test Property',
            'property_type' => 'house',
            'transaction_type' => 'sale',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'parking_lots_data' => [['type' => 'garage', 'count' => 2]],
            'storages_data' => [['type' => 'basement', 'size' => 10]],
            'outdoor_spaces_data' => [['type' => 'garden', 'size' => 50]],
            'images' => ['image1.jpg', 'image2.jpg'],
            'videos' => ['video1.mp4'],
            'floor_plans' => ['plan1.pdf'],
            'features' => ['feature1', 'feature2'],
            'amenities' => ['amenity1'],
            'data' => ['key' => 'value'],
        ]);

        $this->assertIsArray($propertyUnit->parking_lots_data);
        $this->assertIsArray($propertyUnit->storages_data);
        $this->assertIsArray($propertyUnit->outdoor_spaces_data);
        $this->assertIsArray($propertyUnit->images);
        $this->assertIsArray($propertyUnit->videos);
        $this->assertIsArray($propertyUnit->floor_plans);
        $this->assertIsArray($propertyUnit->features);
        $this->assertIsArray($propertyUnit->amenities);
        $this->assertIsArray($propertyUnit->data);
    }

    public function test_casts_boolean_fields(): void
    {
        $propertyUnit = PropertyUnit::create([
            'title' => 'Test Property',
            'property_type' => 'house',
            'transaction_type' => 'sale',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'berth' => true,
            'garage' => false,
            'has_parking' => true,
            'has_elevator' => false,
            'has_ac' => true,
            'has_alarm' => false,
        ]);

        $this->assertIsBool($propertyUnit->berth);
        $this->assertIsBool($propertyUnit->garage);
        $this->assertIsBool($propertyUnit->has_parking);
        $this->assertIsBool($propertyUnit->has_elevator);
        $this->assertIsBool($propertyUnit->has_ac);
        $this->assertIsBool($propertyUnit->has_alarm);
    }

    public function test_casts_date_fields(): void
    {
        $propertyUnit = PropertyUnit::create([
            'title' => 'Test Property',
            'property_type' => 'house',
            'transaction_type' => 'sale',
            'listing_date' => '2025-01-01',
            'viewing_date' => '2025-01-15 10:00:00',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'last_changed_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $propertyUnit->listing_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $propertyUnit->viewing_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $propertyUnit->first_seen_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $propertyUnit->last_seen_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $propertyUnit->last_changed_at);
    }
}
