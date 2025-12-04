<?php

namespace Tests\Feature\Property;

use App\Models\PropertyUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('oxxen');
    }

    public function test_homepage_displays_oxxen_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Oxxen');
        $response->assertSee('Your next home hides in plain sight.');
    }

    public function test_homepage_has_email_signup_form(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Notify me');
        $response->assertSee('email');
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('index');
    }

    public function test_home_page_displays_featured_properties(): void
    {
        PropertyUnit::factory()->available()->count(3)->create([
            'title' => 'Featured Property',
        ]);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Featured Property');
    }

    public function test_home_page_shows_only_available_properties(): void
    {
        PropertyUnit::factory()->available()->create(['title' => 'Available Property']);
        PropertyUnit::factory()->sold()->create(['title' => 'Sold Property']);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Available Property');
        $response->assertDontSee('Sold Property');
    }

    public function test_home_page_limits_featured_to_six(): void
    {
        PropertyUnit::factory()->available()->count(10)->create();

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('featuredProperties', function ($properties) {
            return $properties->count() === 6;
        });
    }

    public function test_home_page_shows_most_recent_properties(): void
    {
        PropertyUnit::factory()->available()->create([
            'title' => 'Old Property',
            'created_at' => now()->subDays(10),
        ]);
        PropertyUnit::factory()->available()->create([
            'title' => 'New Property',
            'created_at' => now(),
        ]);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('featuredProperties', function ($properties) {
            return $properties->first()->title === 'New Property';
        });
    }

    public function test_home_page_works_with_no_properties(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('featuredProperties', function ($properties) {
            return $properties->isEmpty();
        });
    }
}
