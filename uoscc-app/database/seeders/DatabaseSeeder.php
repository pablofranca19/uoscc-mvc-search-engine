<?php

namespace Database\Seeders;

use App\Enums\ActivityType;
use App\Enums\FocusArea;
use App\Models\Activity;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Builds the six UOSCC organizations (one per focus area), each with a
     * rentable space and at least one public activity. This satisfies the
     * acceptance criteria:
     *   1. An OSC registers an event/project with date, location, description
     *      and (optionally) number of spots.
     *   2. The event is in the public area, visible without login.
     *   3. A visitor can filter by activity type or date and find the event.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'Instituto Educar Camaçari',
                'focus_area' => FocusArea::EducationAndCitizenship,
                'description' => 'Promove educação e formação cidadã na comunidade.',
            ],
            [
                'name' => 'Verde Vivo Sustentabilidade',
                'focus_area' => FocusArea::EnvironmentAndSustainability,
                'description' => 'Ações de meio ambiente e sustentabilidade.',
            ],
            [
                'name' => 'Saúde para Todos',
                'focus_area' => FocusArea::HealthcareAndCommunityWellbeing,
                'description' => 'Cuidados de saúde e bem-estar comunitário.',
            ],
            [
                'name' => 'Rede Apoio Família',
                'focus_area' => FocusArea::SocialAssistanceAndFamilySupport,
                'description' => 'Assistência social e apoio às famílias.',
            ],
            [
                'name' => 'Direitos em Movimento',
                'focus_area' => FocusArea::HumanRightsAndSocialInclusion,
                'description' => 'Defesa de direitos humanos e inclusão social.',
            ],
            [
                'name' => 'Cultura Viva Camaçari',
                'focus_area' => FocusArea::CultureSportsAndLeisure,
                'description' => 'Cultura, esporte e lazer para a comunidade.',
            ],
        ];

        foreach ($organizations as $data) {
            $organization = Organization::factory()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'focus_area' => $data['focus_area'],
            ]);

            // A supporter that can manage the organization.
            User::factory()->supporter()->create([
                'name' => 'Gestor '.$data['name'],
                'organization_id' => $organization->id,
            ]);

            // A rentable space with its own location.
            $location = Location::factory()->create([
                'neighbourhood' => 'Centro',
                'street' => 'Rua das Flores',
            ]);

            $space = Space::factory()->create([
                'name' => 'Auditório '.$data['name'],
                'organization_id' => $organization->id,
                'location_id' => $location->id,
            ]);

            // One Event and one Project per organization, with open registration.
            Activity::factory()
                ->ofType(ActivityType::Event)
                ->create([
                    'organization_id' => $organization->id,
                    'space_id' => $space->id,
                ]);

            Activity::factory()
                ->ofType(ActivityType::Project)
                ->create([
                    'organization_id' => $organization->id,
                    'space_id' => $space->id,
                ]);
        }

        // A deterministic, well-known event that proves criteria 1-3 end to end:
        // registered by an OSC, with date/location/description/vacancies, public,
        // and findable by type (Event) and by date.
        $showcaseOrg = Organization::query()->where('name', 'Cultura Viva Camaçari')->firstOrFail();
        $showcaseSpace = $showcaseOrg->spaces()->firstOrFail();

        Activity::factory()->create([
            'organization_id' => $showcaseOrg->id,
            'space_id' => $showcaseSpace->id,
            'title' => 'Feira Cultural Aberta UOSCC',
            'description' => 'Feira cultural aberta ao público com oficinas e apresentações.',
            'activity_date' => now()->addWeeks(2)->toDateString(),
            'activity_type' => ActivityType::Event,
            'capacity' => 120,
            'registration_deadline' => now()->addWeek()->toDateString(),
        ]);
    }
}
