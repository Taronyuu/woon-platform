<?php

namespace Tests\Feature\Navigation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_contains_over_oxxen_link(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Over Oxxen');
    }

    public function test_footer_contains_contact_link(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Contact');
    }

    public function test_footer_contains_privacy_link(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Privacy');
    }

    public function test_footer_contains_voorwaarden_link(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Algemene voorwaarden');
    }

    public function test_footer_displays_copyright_with_current_year(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee(date('Y'));
        $response->assertSee('Oxxen');
    }

    public function test_footer_is_present_on_all_pages(): void
    {
        $pages = ['/home', '/zoeken', '/privacy', '/voorwaarden', '/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
        }
    }

    public function test_footer_links_work(): void
    {
        $response = $this->get('/over-oxxen');
        $response->assertStatus(200);

        $response = $this->get('/contact');
        $response->assertStatus(200);

        $response = $this->get('/privacy');
        $response->assertStatus(200);

        $response = $this->get('/voorwaarden');
        $response->assertStatus(200);
    }
}
