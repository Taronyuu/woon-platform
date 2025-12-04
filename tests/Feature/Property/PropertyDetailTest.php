<?php

namespace Tests\Feature\Property;

use App\Models\PropertyUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_detail_page_loads_successfully(): void
    {
        $property = PropertyUnit::factory()->create();

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertViewIs('detailpagina');
    }

    public function test_property_displays_title(): void
    {
        $property = PropertyUnit::factory()->create(['title' => 'Beautiful Family Home']);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('Beautiful Family Home');
    }

    public function test_property_displays_address(): void
    {
        $property = PropertyUnit::factory()->create([
            'address_street' => 'Hoofdstraat',
            'address_number' => '123',
            'address_city' => 'Amsterdam',
        ]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('Hoofdstraat');
        $response->assertSee('123');
        $response->assertSee('Amsterdam');
    }

    public function test_property_displays_price_for_sale(): void
    {
        $property = PropertyUnit::factory()->sale()->create(['buyprice' => 350000]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('350.000');
    }

    public function test_property_displays_price_for_rent(): void
    {
        $property = PropertyUnit::factory()->rent()->create(['rentprice_month' => 1500]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('1.500');
    }

    public function test_property_displays_surface(): void
    {
        $property = PropertyUnit::factory()->create(['surface' => 120]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('120');
    }

    public function test_property_displays_bedrooms(): void
    {
        $property = PropertyUnit::factory()->create(['bedrooms' => 4]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('4');
    }

    public function test_property_with_agent_info_shows_contact(): void
    {
        $property = PropertyUnit::factory()->withAgent()->create([
            'agent_name' => 'Jan Makelaar',
            'agent_phone' => '0612345678',
            'agent_email' => 'jan@makelaardij.nl',
        ]);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('Jan Makelaar');
        $response->assertSee('0612345678');
        $response->assertSee('jan@makelaardij.nl');
    }

    public function test_property_without_agent_info(): void
    {
        $property = PropertyUnit::factory()->withoutAgent()->create();

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
    }

    public function test_invalid_property_slug_returns_404(): void
    {
        $response = $this->get('/woningen/non-existent-property-slug');

        $response->assertStatus(404);
    }

    public function test_property_type_displayed_in_dutch(): void
    {
        $property = PropertyUnit::factory()->create(['property_type' => 'apartment']);

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('Appartement');
    }

    public function test_transaction_type_displayed_in_dutch(): void
    {
        $property = PropertyUnit::factory()->sale()->create();

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
        $response->assertSee('Te koop');
    }

    public function test_property_displays_energy_label(): void
    {
        $property = PropertyUnit::factory()->withEnergyLabel('A')->create();

        $response = $this->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
    }

    public function test_authenticated_user_sees_favorite_option(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create();

        $response = $this->actingAs($user)->get("/woningen/{$property->slug}");

        $response->assertStatus(200);
    }
}
