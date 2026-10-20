<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'participant_name' => fake()->name(),
            'participant_contact' => fake()->safeEmail(),
            'registered_at' => now(),
        ];
    }
}
