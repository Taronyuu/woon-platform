<?php

namespace Tests\Feature\Security;

use App\Models\SearchProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_update_another_users_search_profile(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $profile = SearchProfile::factory()->forUser($user1)->create();

        $response = $this->actingAs($user2)->put("/account/zoekprofielen/{$profile->id}", [
            'name' => 'Updated Name',
        ]);

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_user_cannot_delete_another_users_search_profile(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $profile = SearchProfile::factory()->forUser($user1)->create();

        $response = $this->actingAs($user2)->delete("/account/zoekprofielen/{$profile->id}");

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_guest_cannot_access_account_pages(): void
    {
        $response = $this->get('/account');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_create_search_profile(): void
    {
        $response = $this->post('/account/zoekprofielen', [
            'name' => 'Test Profile',
        ]);

        $response->assertRedirect('/login');
    }
}
