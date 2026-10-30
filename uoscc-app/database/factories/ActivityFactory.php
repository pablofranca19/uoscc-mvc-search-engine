<?php

namespace Database\Factories;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activityDate = fake()->dateTimeBetween('+1 week', '+2 months');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'activity_date' => $activityDate,
            'space_id' => Space::factory(),
            'organization_id' => Organization::factory(),
            'activity_type' => fake()->randomElement(ActivityType::cases()),
            'capacity' => fake()->numberBetween(20, 100),
            'registration_deadline' => fake()->dateTimeBetween('now', $activityDate),
        ];
    }

    /**
     * Activity with no capacity limit (unlimited registrations).
     */
    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => null,
        ]);
    }

    /**
     * Activity with no registration (no deadline/capacity data).
     */
    public function withoutRegistration(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => null,
            'registration_deadline' => null,
        ]);
    }

    /**
     * Activity of the given type.
     */
    public function ofType(ActivityType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'activity_type' => $type,
        ]);
    }
}
