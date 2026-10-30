<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\SupporterFavorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupporterFavorite>
 */
class SupporterFavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->supporter(),
            'favoritable_type' => Organization::class,
            'favoritable_id' => Organization::factory(),
        ];
    }

    /**
     * Indicate that the favorite points at an activity.
     */
    public function forActivity(?Activity $activity = null): static
    {
        return $this->state(fn (array $attributes) => [
            'favoritable_type' => Activity::class,
            'favoritable_id' => $activity?->id ?? Activity::factory(),
        ]);
    }

    /**
     * Indicate that the favorite points at an organization.
     */
    public function forOrganization(?Organization $organization = null): static
    {
        return $this->state(fn (array $attributes) => [
            'favoritable_type' => Organization::class,
            'favoritable_id' => $organization?->id ?? Organization::factory(),
        ]);
    }
}
