<?php

namespace Database\Factories;

use App\Models\PropertyUnit;
use App\Models\SearchProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'type' => 'consumer',
            'notify_new_properties' => true,
            'notify_price_changes' => true,
            'notify_newsletter' => false,
            'notify_marketing' => false,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function realtor(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'makelaar',
        ]);
    }

    public function withNotificationsEnabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'notify_new_properties' => true,
            'notify_price_changes' => true,
            'notify_newsletter' => true,
            'notify_marketing' => true,
        ]);
    }

    public function withNotificationsDisabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'notify_new_properties' => false,
            'notify_price_changes' => false,
            'notify_newsletter' => false,
            'notify_marketing' => false,
        ]);
    }

    public function withProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
        ]);
    }

    public function withSearchProfiles(int $count = 1): static
    {
        return $this->afterCreating(function ($user) use ($count) {
            SearchProfile::factory()->count($count)->forUser($user)->create();
        });
    }

    public function withMaxSearchProfiles(): static
    {
        return $this->withSearchProfiles(5);
    }

    public function withFavorites(int $count = 1): static
    {
        return $this->afterCreating(function ($user) use ($count) {
            $properties = PropertyUnit::factory()->count($count)->create();
            $user->favoriteProperties()->attach($properties->pluck('id'));
        });
    }
}
