<?php

namespace Tests\Feature\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_account_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/account');

        $response->assertStatus(200);
        $response->assertViewIs('account-consument');
    }

    public function test_user_can_update_first_name(): void
    {
        $user = User::factory()->create(['first_name' => 'Jan']);

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => 'Piet',
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Je gegevens zijn succesvol bijgewerkt.');
        $this->assertEquals('Piet', $user->fresh()->first_name);
    }

    public function test_user_can_update_last_name(): void
    {
        $user = User::factory()->create(['last_name' => 'Jansen']);

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => $user->first_name,
            'last_name' => 'de Vries',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertEquals('de Vries', $user->fresh()->last_name);
    }

    public function test_user_can_update_phone(): void
    {
        $user = User::factory()->create(['phone' => '0612345678']);

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => '0687654321',
        ]);

        $response->assertRedirect();
        $this->assertEquals('0687654321', $user->fresh()->phone);
    }

    public function test_user_can_update_email(): void
    {
        $user = User::factory()->create(['email' => 'old@example.com']);

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect();
        $this->assertEquals('new@example.com', $user->fresh()->email);
    }

    public function test_email_update_validates_uniqueness(): void
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        $user = User::factory()->create(['email' => 'current@example.com']);

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_profile_update_shows_success_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/account/profile', [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $user->email,
        ]);

        $response->assertSessionHas('success', 'Je gegevens zijn succesvol bijgewerkt.');
    }

    public function test_unauthenticated_user_cannot_update_profile(): void
    {
        $response = $this->post('/account/profile', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/login');
    }
}
