<?php

namespace Database\Factories;

use App\Models\Osc;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Osc>
 */
class OscFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->company(),
            'area_atuacao' => fake()->randomElement(['Educação', 'Meio ambiente', 'Assistência social']),
            'email' => fake()->companyEmail(),
            'telefone' => fake()->phoneNumber(),
            'redes_sociais' => fake()->url(),
            'descricao' => fake()->paragraph(),
        ];
    }
}
