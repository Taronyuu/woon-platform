<?php

namespace Tests\Feature\Property;

use App\Models\PropertyUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_loads_successfully(): void
    {
        $response = $this->get('/zoeken');

        $response->assertStatus(200);
        $response->assertViewIs('zoeken');
    }

    public function test_search_by_location_filters_results(): void
    {
        PropertyUnit::factory()->available()->inCity('Amsterdam')->create(['title' => 'Amsterdam Property']);
        PropertyUnit::factory()->available()->inCity('Rotterdam')->create(['title' => 'Rotterdam Property']);

        $response = $this->get('/zoeken?search=Amsterdam');

        $response->assertStatus(200);
        $response->assertSee('Amsterdam Property');
        $response->assertDontSee('Rotterdam Property');
    }

    public function test_filter_by_type_sale_shows_only_sale_properties(): void
    {
        PropertyUnit::factory()->sale()->available()->create(['title' => 'Sale Property']);
        PropertyUnit::factory()->rent()->available()->create(['title' => 'Rental Property']);

        $response = $this->get('/zoeken?type=sale');

        $response->assertStatus(200);
        $response->assertSee('Sale Property');
        $response->assertDontSee('Rental Property');
    }

    public function test_filter_by_type_rent_shows_only_rental_properties(): void
    {
        PropertyUnit::factory()->sale()->available()->create(['title' => 'Sale Property']);
        PropertyUnit::factory()->rent()->available()->create(['title' => 'Rental Property']);

        $response = $this->get('/zoeken?type=rent');

        $response->assertStatus(200);
        $response->assertSee('Rental Property');
        $response->assertDontSee('Sale Property');
    }

    public function test_filter_by_price_range_works(): void
    {
        PropertyUnit::factory()->sale()->available()->create([
            'title' => 'Cheap Property',
            'buyprice' => 100000,
        ]);
        PropertyUnit::factory()->sale()->available()->create([
            'title' => 'Expensive Property',
            'buyprice' => 500000,
        ]);

        $response = $this->get('/zoeken?min_price=200000&max_price=600000');

        $response->assertStatus(200);
        $response->assertSee('Expensive Property');
        $response->assertDontSee('Cheap Property');
    }

    public function test_filter_by_surface_range_works(): void
    {
        PropertyUnit::factory()->available()->create([
            'title' => 'Small Property',
            'surface' => 50,
        ]);
        PropertyUnit::factory()->available()->create([
            'title' => 'Large Property',
            'surface' => 150,
        ]);

        $response = $this->get('/zoeken?min_surface=100&max_surface=200');

        $response->assertStatus(200);
        $response->assertSee('Large Property');
        $response->assertDontSee('Small Property');
    }

    public function test_filter_by_rooms_works(): void
    {
        PropertyUnit::factory()->available()->create([
            'title' => 'Small House',
            'bedrooms' => 2,
        ]);
        PropertyUnit::factory()->available()->create([
            'title' => 'Large House',
            'bedrooms' => 5,
        ]);

        $response = $this->get('/zoeken?rooms=4');

        $response->assertStatus(200);
        $response->assertSee('Large House');
        $response->assertDontSee('Small House');
    }

    public function test_filter_by_energy_label_works(): void
    {
        PropertyUnit::factory()->available()->withEnergyLabel('A')->create(['title' => 'Energy A Property']);
        PropertyUnit::factory()->available()->withEnergyLabel('D')->create(['title' => 'Energy D Property']);

        $response = $this->get('/zoeken?energy_label=A');

        $response->assertStatus(200);
        $response->assertSee('Energy A Property');
        $response->assertDontSee('Energy D Property');
    }

    public function test_combining_multiple_filters_works(): void
    {
        PropertyUnit::factory()->sale()->available()->inCity('Amsterdam')->create([
            'title' => 'Perfect Match',
            'buyprice' => 300000,
            'surface' => 100,
        ]);
        PropertyUnit::factory()->rent()->available()->inCity('Amsterdam')->create([
            'title' => 'Wrong Type',
        ]);
        PropertyUnit::factory()->sale()->available()->inCity('Rotterdam')->create([
            'title' => 'Wrong City',
            'buyprice' => 300000,
        ]);

        $response = $this->get('/zoeken?type=sale&search=Amsterdam&min_price=200000');

        $response->assertStatus(200);
        $response->assertSee('Perfect Match');
        $response->assertDontSee('Wrong Type');
        $response->assertDontSee('Wrong City');
    }

    public function test_sort_by_price_orders_correctly(): void
    {
        PropertyUnit::factory()->sale()->available()->create([
            'title' => 'Expensive',
            'buyprice' => 500000,
        ]);
        PropertyUnit::factory()->sale()->available()->create([
            'title' => 'Cheap',
            'buyprice' => 100000,
        ]);

        $response = $this->get('/zoeken?sort=buyprice&order=asc');

        $response->assertStatus(200);
        $response->assertViewHas('properties', function ($properties) {
            return $properties->first()->title === 'Cheap';
        });
    }

    public function test_sort_by_surface_orders_correctly(): void
    {
        PropertyUnit::factory()->available()->create([
            'title' => 'Large',
            'surface' => 200,
        ]);
        PropertyUnit::factory()->available()->create([
            'title' => 'Small',
            'surface' => 50,
        ]);

        $response = $this->get('/zoeken?sort=surface&order=desc');

        $response->assertStatus(200);
        $response->assertViewHas('properties', function ($properties) {
            return $properties->first()->title === 'Large';
        });
    }

    public function test_pagination_works(): void
    {
        PropertyUnit::factory()->available()->count(25)->create();

        $response = $this->get('/zoeken');

        $response->assertStatus(200);
        $response->assertViewHas('properties', function ($properties) {
            return $properties->count() === 12;
        });
    }

    public function test_second_page_shows_different_results(): void
    {
        PropertyUnit::factory()->available()->count(15)->create();

        $response = $this->get('/zoeken?page=2');

        $response->assertStatus(200);
        $response->assertViewHas('properties', function ($properties) {
            return $properties->count() === 3;
        });
    }

    public function test_url_reflects_filters(): void
    {
        $response = $this->get('/zoeken?type=sale&min_price=100000');

        $response->assertStatus(200);
        $response->assertViewHas('filters', function ($filters) {
            return $filters['type'] === 'sale' && $filters['min_price'] === '100000';
        });
    }

    public function test_only_available_properties_shown(): void
    {
        PropertyUnit::factory()->available()->create(['title' => 'Available']);
        PropertyUnit::factory()->sold()->create(['title' => 'Sold']);
        PropertyUnit::factory()->rented()->create(['title' => 'Rented']);

        $response = $this->get('/zoeken');

        $response->assertStatus(200);
        $response->assertSee('Available');
        $response->assertDontSee('Sold');
        $response->assertDontSee('Rented');
    }

    public function test_no_results_returns_empty_collection(): void
    {
        $response = $this->get('/zoeken?search=NonExistentCity');

        $response->assertStatus(200);
        $response->assertViewHas('properties', function ($properties) {
            return $properties->isEmpty();
        });
    }

    public function test_ajax_request_returns_json(): void
    {
        PropertyUnit::factory()->available()->count(3)->create();

        $response = $this->getJson('/zoeken');

        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'pagination', 'total']);
    }
}
