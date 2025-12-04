<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_new_users_can_register_with_required_fields(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'type' => 'consumer',
        ]);
    }

    public function test_new_users_can_register_with_all_fields(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'first_name' => 'Jan',
            'last_name' => 'Jansen',
            'phone' => '0612345678',
            'address' => 'Hoofdstraat 1',
            'postal_code' => '1234 AB',
            'city' => 'Amsterdam',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'first_name' => 'Jan',
            'last_name' => 'Jansen',
            'phone' => '0612345678',
            'address' => 'Hoofdstraat 1',
            'postal_code' => '1234 AB',
            'city' => 'Amsterdam',
        ]);
    }

    public function test_registration_fails_with_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertInvalid(['email' => 'Dit e-mailadres is al in gebruik.']);
    }

    public function test_registration_fails_without_email(): void
    {
        $response = $this->post('/register', [
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertInvalid(['email' => 'E-mailadres is verplicht.']);
    }

    public function test_registration_fails_without_password(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertInvalid(['password' => 'Wachtwoord is verplicht.']);
    }

    public function test_registration_fails_with_password_mismatch(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertInvalid(['password' => 'Wachtwoorden komen niet overeen.']);
    }

    public function test_registration_fails_with_short_password(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertInvalid(['password' => 'Wachtwoord moet minimaal 8 tekens bevatten.']);
    }

    public function test_registration_sends_verification_email(): void
    {
        Mail::fake();

        $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        Mail::assertSent(\App\Mail\VerifyEmail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }
}
