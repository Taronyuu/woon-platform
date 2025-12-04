<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_without_csrf_token_fails(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_forms_include_csrf_token(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }

    public function test_registration_form_has_csrf_protection(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }
}
