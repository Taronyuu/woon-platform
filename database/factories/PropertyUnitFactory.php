<?php

namespace Database\Factories;

use App\Models\PropertyUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyUnitFactory extends Factory
{
    protected $model = PropertyUnit::class;

    public function definition(): array
    {
        $cities = ['Amsterdam', 'Rotterdam', 'Utrecht', 'Den Haag', 'Eindhoven', 'Groningen', 'Tilburg', 'Almere'];
        $city = fake()->randomElement($cities);
        $street = fake()->streetName();
        $postalCode = fake()->postcode();

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'property_type' => fake()->randomElement(['house', 'apartment', 'villa', 'townhouse']),
            'transaction_type' => fake()->randomElement(['sale', 'rent']),
            'status' => 'available',
            'address_street' => $street,
            'address_number' => fake()->buildingNumber(),
            'address_city' => $city,
            'address_postal_code' => $postalCode,
            'address_province' => fake()->randomElement(['Noord-Holland', 'Zuid-Holland', 'Utrecht', 'Noord-Brabant']),
            'address_country' => 'NL',
            'surface' => fake()->numberBetween(50, 300),
            'bedrooms' => fake()->numberBetween(1, 6),
            'bathrooms' => fake()->numberBetween(1, 3),
            'buyprice' => fake()->numberBetween(150000, 800000),
            'rentprice_month' => fake()->numberBetween(800, 3000),
            'energy_label' => fake()->randomElement(['A++', 'A+', 'A', 'B', 'C', 'D']),
            'first_seen_at' => now()->subDays(fake()->numberBetween(1, 90)),
            'last_seen_at' => now(),
            'images' => [],
        ];
    }

    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'sale',
            'buyprice' => fake()->numberBetween(150000, 800000),
        ]);
    }

    public function rent(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'rent',
            'rentprice_month' => fake()->numberBetween(800, 3000),
        ]);
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
        ]);
    }

    public function rented(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rented',
        ]);
    }

    public function withAgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'agent_name' => fake()->name(),
            'agent_company' => fake()->company(),
            'agent_phone' => fake()->phoneNumber(),
            'agent_email' => fake()->safeEmail(),
        ]);
    }

    public function withoutAgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'agent_name' => null,
            'agent_company' => null,
            'agent_phone' => null,
            'agent_email' => null,
        ]);
    }

    public function withImages(int $count = 3): static
    {
        return $this->state(fn (array $attributes) => [
            'images' => array_map(
                fn () => 'https://picsum.photos/800/600?random=' . fake()->unique()->randomNumber(),
                range(1, $count)
            ),
        ]);
    }

    public function inCity(string $city): static
    {
        return $this->state(fn (array $attributes) => [
            'address_city' => $city,
        ]);
    }

    public function withEnergyLabel(string $label): static
    {
        return $this->state(fn (array $attributes) => [
            'energy_label' => $label,
        ]);
    }

    public function withSurface(int $surface): static
    {
        return $this->state(fn (array $attributes) => [
            'surface' => $surface,
        ]);
    }

    public function withBedrooms(int $bedrooms): static
    {
        return $this->state(fn (array $attributes) => [
            'bedrooms' => $bedrooms,
        ]);
    }

    public function withPrice(int $price): static
    {
        return $this->state(fn (array $attributes) => [
            'buyprice' => $price,
            'rentprice_month' => $price,
        ]);
    }
}
