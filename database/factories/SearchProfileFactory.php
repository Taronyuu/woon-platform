<?php

namespace Database\Factories;

use App\Models\SearchProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SearchProfileFactory extends Factory
{
    protected $model = SearchProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true) . ' zoekprofiel',
            'transaction_type' => null,
            'cities' => null,
            'min_price' => null,
            'max_price' => null,
            'min_surface' => null,
            'max_surface' => null,
            'min_bedrooms' => null,
            'property_type' => null,
            'energy_label' => null,
            'is_active' => true,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function forSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'sale',
        ]);
    }

    public function forRent(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'rent',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withCities(array $cities): static
    {
        return $this->state(fn (array $attributes) => [
            'cities' => $cities,
        ]);
    }

    public function withPriceRange(int $min, int $max): static
    {
        return $this->state(fn (array $attributes) => [
            'min_price' => $min,
            'max_price' => $max,
        ]);
    }

    public function withSurfaceRange(int $min, int $max): static
    {
        return $this->state(fn (array $attributes) => [
            'min_surface' => $min,
            'max_surface' => $max,
        ]);
    }

    public function withBedrooms(int $min): static
    {
        return $this->state(fn (array $attributes) => [
            'min_bedrooms' => $min,
        ]);
    }

    public function full(): static
    {
        return $this->state(fn (array $attributes) => [
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
    }
}
