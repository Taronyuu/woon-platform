<?php

namespace Tests\Feature\StaticPages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_page_loads_successfully(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
    }

    public function test_privacy_page_contains_dutch_content(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('Privacy');
    }

    public function test_terms_page_loads_successfully(): void
    {
        $response = $this->get('/voorwaarden');

        $response->assertStatus(200);
    }

    public function test_terms_page_contains_dutch_content(): void
    {
        $response = $this->get('/voorwaarden');

        $response->assertStatus(200);
        $response->assertSee('Algemene');
    }

    public function test_about_page_loads_successfully(): void
    {
        $response = $this->get('/over-oxxen');

        $response->assertStatus(200);
    }

    public function test_about_page_contains_mission_content(): void
    {
        $response = $this->get('/over-oxxen');

        $response->assertStatus(200);
        $response->assertSee('Oxxen');
    }

    public function test_contact_page_loads_successfully(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_contact_page_displays_contact_info(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('Contact');
    }

    public function test_mortgage_calculator_page_loads(): void
    {
        $response = $this->get('/maandlasten');

        $response->assertStatus(200);
    }

    public function test_all_static_pages_have_header(): void
    {
        $pages = ['/privacy', '/voorwaarden', '/over-oxxen', '/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('Oxxen');
        }
    }

    public function test_all_static_pages_have_footer(): void
    {
        $pages = ['/privacy', '/voorwaarden', '/over-oxxen', '/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee(date('Y'));
        }
    }

    public function test_privacy_page_accessible_from_anywhere(): void
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
    }

    public function test_terms_page_accessible_from_anywhere(): void
    {
        $response = $this->get('/voorwaarden');
        $response->assertStatus(200);
    }

    public function test_about_page_accessible_from_anywhere(): void
    {
        $response = $this->get('/over-oxxen');
        $response->assertStatus(200);
    }
}
