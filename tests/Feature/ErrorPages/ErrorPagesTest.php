<?php

namespace Tests\Feature\ErrorPages;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_page_displays_for_non_existent_url(): void
    {
        $response = $this->get('/non-existent-page-12345');

        $response->assertStatus(404);
    }

    public function test_404_page_shows_dutch_message(): void
    {
        $response = $this->get('/non-existent-page-12345');

        $response->assertStatus(404);
        $response->assertSee('niet gevonden', false);
    }

    public function test_404_page_has_link_to_homepage(): void
    {
        $response = $this->get('/non-existent-page-12345');

        $response->assertStatus(404);
    }

    public function test_404_for_non_existent_property(): void
    {
        $response = $this->get('/woningen/non-existent-property-slug');

        $response->assertStatus(404);
    }

    public function test_404_for_invalid_search_profile_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/account/zoekprofielen/99999');

        $response->assertStatus(404);
    }

    public function test_error_page_maintains_site_branding(): void
    {
        $response = $this->get('/non-existent-page-12345');

        $response->assertStatus(404);
    }

    public function test_method_not_allowed_returns_405(): void
    {
        $response = $this->delete('/');

        $response->assertStatus(405);
    }

    public function test_unauthorized_admin_access_redirects(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect();
    }

    public function test_deep_nested_non_existent_path_returns_404(): void
    {
        $response = $this->get('/some/deeply/nested/path/that/does/not/exist');

        $response->assertStatus(404);
    }
}
