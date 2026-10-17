<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Organization;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Space>
 */
class SpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'capacity' => fake()->numberBetween(10, 200),
            'facility' => fake()->randomElement(['Projetor', 'Som', 'Ar-condicionado', 'Wi-Fi', 'Cozinha']),
            'location_id' => Location::factory(),
            'organization_id' => Organization::factory(),
        ];
    }
}
