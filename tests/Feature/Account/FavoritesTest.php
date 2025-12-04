<?php

namespace Tests\Feature\Account;

use App\Models\PropertyUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_favorite(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create();

        $response = $this->actingAs($user)->postJson("/woningen/{$property->slug}/favorite");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Woning toegevoegd aan favorieten',
            'is_favorited' => true,
        ]);
        $this->assertTrue($user->hasFavorited($property));
    }

    public function test_authenticated_user_can_remove_favorite(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create();
        $user->favoriteProperties()->attach($property->id);

        $response = $this->actingAs($user)->deleteJson("/woningen/{$property->slug}/favorite");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Woning verwijderd uit favorieten',
            'is_favorited' => false,
        ]);
        $this->assertFalse($user->hasFavorited($property));
    }

    public function test_favorites_appear_in_account_page(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create(['title' => 'Prachtig Appartement']);
        $user->favoriteProperties()->attach($property->id);

        $response = $this->actingAs($user)->get('/account');

        $response->assertStatus(200);
        $response->assertSee('Prachtig Appartement');
    }

    public function test_unauthenticated_user_cannot_add_favorite(): void
    {
        $property = PropertyUnit::factory()->create();

        $response = $this->postJson("/woningen/{$property->slug}/favorite");

        $response->assertStatus(401);
    }

    public function test_adding_duplicate_favorite_is_idempotent(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create();
        $user->favoriteProperties()->attach($property->id);

        $response = $this->actingAs($user)->postJson("/woningen/{$property->slug}/favorite");

        $response->assertStatus(200);
        $this->assertEquals(1, $user->favoriteProperties()->count());
    }

    public function test_user_can_have_multiple_favorites(): void
    {
        $user = User::factory()->create();
        $properties = PropertyUnit::factory()->count(3)->create();

        foreach ($properties as $property) {
            $this->actingAs($user)->postJson("/woningen/{$property->slug}/favorite");
        }

        $this->assertEquals(3, $user->favoriteProperties()->count());
    }

    public function test_removing_non_favorited_property_succeeds(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/woningen/{$property->slug}/favorite");

        $response->assertStatus(200);
    }

    public function test_favorites_show_property_details(): void
    {
        $user = User::factory()->create();
        $property = PropertyUnit::factory()->sale()->create([
            'title' => 'Luxe Villa',
            'address_city' => 'Amsterdam',
            'buyprice' => 500000,
        ]);
        $user->favoriteProperties()->attach($property->id);

        $response = $this->actingAs($user)->get('/account');

        $response->assertStatus(200);
        $response->assertSee('Luxe Villa');
        $response->assertSee('Amsterdam');
    }
}
