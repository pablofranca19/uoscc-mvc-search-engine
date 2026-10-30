<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Space;
use App\Models\SpaceReservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpaceReservation>
 */
class SpaceReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'space_id' => Space::factory(),
            'requester_name' => fake()->name(),
            'requester_contact' => fake()->safeEmail(),
            'requested_date' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'purpose' => fake()->sentence(12),
            'status' => ReservationStatus::Pending,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReservationStatus::Approved,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReservationStatus::Rejected,
        ]);
    }
}
