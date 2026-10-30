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
            'facilities' => fake()->randomElements(
                ['Projetor', 'Som', 'Ar-condicionado', 'Wi-Fi', 'Cozinha', 'Palco', 'Banheiros', 'Mesas e cadeiras'],
                fake()->numberBetween(2, 4),
            ),
            'location_id' => Location::factory(),
            'organization_id' => Organization::factory(),
        ];
    }
}
