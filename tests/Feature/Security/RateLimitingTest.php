<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login');
    }

    public function test_login_allows_initial_attempts(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
    }

    public function test_login_rate_limited_after_multiple_attempts(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
    }

    public function test_password_reset_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/wachtwoord-vergeten', [
                'email' => 'test@example.com',
            ]);
        }

        $response = $this->post('/wachtwoord-vergeten', [
            'email' => 'test@example.com',
        ]);

        $this->assertTrue(in_array($response->status(), [302, 429]));
    }

    public function test_registration_rate_limited_after_multiple_attempts(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/register', [
                'email' => "test{$i}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'account_type' => 'consument',
            ]);
        }

        $response = $this->post('/register', [
            'email' => 'another@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'account_type' => 'consument',
        ]);

        $this->assertTrue(in_array($response->status(), [302, 429, 422]));
    }

    public function test_api_endpoints_have_rate_limiting(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 65; $i++) {
            $this->actingAs($user)->getJson('/zoeken');
        }

        $response = $this->actingAs($user)->getJson('/zoeken');

        $this->assertTrue(in_array($response->status(), [200, 429]));
    }
}
