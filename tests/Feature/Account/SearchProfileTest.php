<?php

namespace Tests\Feature\Account;

use App\Models\SearchProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_search_profile_with_name_only(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
            'name' => 'Mijn zoekprofiel',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Zoekprofiel aangemaakt.',
        ]);
        $this->assertDatabaseHas('search_profiles', [
            'user_id' => $user->id,
            'name' => 'Mijn zoekprofiel',
        ]);
    }

    public function test_user_can_create_search_profile_with_all_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
            'name' => 'Volledig profiel',
            'transaction_type' => 'sale',
            'cities' => ['Amsterdam', 'Utrecht'],
            'min_price' => 200000,
            'max_price' => 500000,
            'min_surface' => 60,
            'max_surface' => 150,
            'min_bedrooms' => 2,
            'property_type' => 'apartment',
            'energy_label' => 'A',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('search_profiles', [
            'user_id' => $user->id,
            'name' => 'Volledig profiel',
            'transaction_type' => 'sale',
            'min_price' => 200000,
            'max_price' => 500000,
        ]);
    }

    public function test_search_profile_creation_requires_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
            'transaction_type' => 'sale',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_create_up_to_five_profiles(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
                'name' => "Profiel {$i}",
            ]);
            $response->assertStatus(200);
        }

        $this->assertEquals(5, $user->searchProfiles()->count());
    }

    public function test_user_cannot_create_sixth_profile(): void
    {
        $user = User::factory()->withMaxSearchProfiles()->create();

        $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
            'name' => 'Zesde profiel',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Je kunt maximaal 5 zoekprofielen aanmaken.',
        ]);
    }

    public function test_user_can_update_search_profile(): void
    {
        $user = User::factory()->create();
        $profile = SearchProfile::factory()->forUser($user)->create(['name' => 'Origineel']);

        $response = $this->actingAs($user)->putJson("/account/zoekprofielen/{$profile->id}", [
            'name' => 'Bijgewerkt',
            'transaction_type' => 'rent',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Zoekprofiel bijgewerkt.',
        ]);
        $this->assertEquals('Bijgewerkt', $profile->fresh()->name);
    }

    public function test_user_can_delete_search_profile(): void
    {
        $user = User::factory()->create();
        $profile = SearchProfile::factory()->forUser($user)->create();

        $response = $this->actingAs($user)->deleteJson("/account/zoekprofielen/{$profile->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Zoekprofiel verwijderd.',
        ]);
        $this->assertDatabaseMissing('search_profiles', ['id' => $profile->id]);
    }

    public function test_user_can_toggle_profile_active_status(): void
    {
        $user = User::factory()->create();
        $profile = SearchProfile::factory()->forUser($user)->active()->create();

        $response = $this->actingAs($user)->patchJson("/account/zoekprofielen/{$profile->id}/toggle");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Zoekprofiel gedeactiveerd.',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->patchJson("/account/zoekprofielen/{$profile->id}/toggle");

        $response->assertJson([
            'success' => true,
            'message' => 'Zoekprofiel geactiveerd.',
            'is_active' => true,
        ]);
    }

    public function test_cities_stored_as_array(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/account/zoekprofielen', [
            'name' => 'Steden profiel',
            'cities' => ['Amsterdam', 'Rotterdam', 'Utrecht'],
        ]);

        $response->assertStatus(200);
        $profile = $user->searchProfiles()->first();
        $this->assertEquals(['Amsterdam', 'Rotterdam', 'Utrecht'], $profile->cities);
    }

    public function test_user_cannot_update_other_users_profile(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $profile = SearchProfile::factory()->forUser($owner)->create();

        $response = $this->actingAs($otherUser)->putJson("/account/zoekprofielen/{$profile->id}", [
            'name' => 'Gehackt',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Niet geautoriseerd.',
        ]);
    }

    public function test_user_cannot_delete_other_users_profile(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $profile = SearchProfile::factory()->forUser($owner)->create();

        $response = $this->actingAs($otherUser)->deleteJson("/account/zoekprofielen/{$profile->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('search_profiles', ['id' => $profile->id]);
    }

    public function test_unauthenticated_user_cannot_manage_profiles(): void
    {
        $response = $this->postJson('/account/zoekprofielen', [
            'name' => 'Test profiel',
        ]);

        $response->assertStatus(401);
    }
}
