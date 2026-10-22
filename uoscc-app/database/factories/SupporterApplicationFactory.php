<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupporterApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'applicant_type' => 'person',
            'organization_name' => null,
            'interests' => 'Educação e meio ambiente',
            'contribution' => 'Quero contribuir com oficinas e atividades da comunidade.',
            'status' => 'pending',
            'submitted_at' => now(),
        ];
    }
}
