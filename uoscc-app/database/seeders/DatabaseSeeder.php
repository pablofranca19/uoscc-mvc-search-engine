<?php

namespace Database\Seeders;

use App\Enums\ActivityType;
use App\Enums\FocusArea;
use App\Models\Activity;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\Space;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the deterministic UOSCC demo catalog.
     *
     * The data is intentionally fixed (names, ids, dates, capacities) so the
     * public pages render a stable catalog and the feature tests can assert
     * against known values. The relationships model the real domain:
     * an Organization owns Spaces (each with a Location) and hosts Activities
     * (events/projects) in those spaces.
     */
    public function run(): void
    {
        $viverMelhor = $this->makeOrganization(
            'Instituto Viver Melhor',
            FocusArea::SocialAssistanceAndFamilySupport,
            'Atua com ações de cidadania, apoio comunitário e fortalecimento de famílias.',
        );
        $conecta = $this->makeOrganization(
            'Conecta Comunidade',
            FocusArea::EducationAndCitizenship,
            'Promove inclusão digital, formação profissional e acesso à tecnologia.',
        );
        $redeVerde = $this->makeOrganization(
            'Rede Verde Camaçari',
            FocusArea::EnvironmentAndSustainability,
            'Desenvolve projetos de educação ambiental, sustentabilidade e preservação.',
        );
        $movimentoJovem = $this->makeOrganization(
            'Movimento Jovem UOSCC',
            FocusArea::CultureSportsAndLeisure,
            'Incentiva protagonismo juvenil, cultura e participação cidadã.',
        );

        // Spaces (each owned by an organization and placed at a location).
        $espacoConecta = $this->makeSpace(
            $conecta,
            'Espaço Conecta',
            'Orla',
            40,
            ['Wi-Fi', 'Projetor', 'Ar-condicionado', 'Mesas e cadeiras'],
            'Sala multiuso para cursos, reuniões, oficinas e pequenas apresentações.',
        );
        $salaoViver = $this->makeSpace(
            $viverMelhor,
            'Salão Viver Melhor',
            'Centro',
            120,
            ['Palco', 'Som', 'Projetor', 'Banheiros'],
            'Salão amplo para eventos comunitários, palestras e atividades coletivas.',
        );
        $casaVerde = $this->makeSpace(
            $redeVerde,
            'Casa Verde',
            'Parque Verde',
            50,
            ['Área externa', 'Copa', 'Wi-Fi', 'Mesas'],
            'Espaço integrado à área verde, ideal para encontros, oficinas e atividades ambientais.',
        );
        $casaJuventude = $this->makeSpace(
            $movimentoJovem,
            'Casa da Juventude',
            'Gleba A',
            60,
            ['Wi-Fi', 'Som', 'Mesas e cadeiras'],
            'Espaço de convivência para encontros, rodas de conversa e atividades culturais.',
        );

        // Activity 1 — open event: future date and future deadline, spots left.
        $feira = Activity::factory()->create([
            'organization_id' => $viverMelhor->id,
            'space_id' => $salaoViver->id,
            'title' => 'Feira de Cidadania e Serviços',
            'description' => 'Um dia de serviços, orientação e atividades para aproximar a comunidade das organizações da sociedade civil.',
            'activity_date' => '2026-10-24 09:00:00',
            'activity_type' => ActivityType::Event,
            'capacity' => 120,
            'registration_deadline' => '2026-10-23',
        ]);
        $this->makeRegistrations($feira, 78);

        // Activity 2 — open event hosted by Conecta Comunidade.
        Activity::factory()->create([
            'organization_id' => $conecta->id,
            'space_id' => $espacoConecta->id,
            'title' => 'Oficina de Inclusão Digital',
            'description' => 'Oficina prática para pessoas que querem desenvolver habilidades digitais para estudo, trabalho e serviços públicos.',
            'activity_date' => '2026-10-29 14:00:00',
            'activity_type' => ActivityType::Event,
            'capacity' => 30,
            'registration_deadline' => '2026-10-28',
        ]);

        // Activity 3 — closed event: the registration deadline is already in the past.
        Activity::factory()->create([
            'organization_id' => $redeVerde->id,
            'space_id' => $casaVerde->id,
            'title' => 'Mutirão Verde',
            'description' => 'Ação coletiva de educação ambiental, limpeza e plantio de mudas em área pública.',
            'activity_date' => '2026-11-07 08:00:00',
            'activity_type' => ActivityType::Event,
            'capacity' => 80,
            'registration_deadline' => '2026-10-14',
        ]);

        // Activity 4 — full event: registrations equal the capacity.
        $encontro = Activity::factory()->create([
            'organization_id' => $movimentoJovem->id,
            'space_id' => $casaJuventude->id,
            'title' => 'Encontro de Juventudes',
            'description' => 'Roda de conversa, cultura e construção de propostas para ampliar a participação da juventude na comunidade.',
            'activity_date' => '2026-11-14 15:00:00',
            'activity_type' => ActivityType::Event,
            'capacity' => 60,
            'registration_deadline' => '2026-11-13',
        ]);
        $this->makeRegistrations($encontro, 60);

        // Activity 5 — project hosted by Conecta Comunidade.
        Activity::factory()->create([
            'organization_id' => $conecta->id,
            'space_id' => $espacoConecta->id,
            'title' => 'Trilhas Digitais — projeto demonstrativo',
            'description' => 'Exemplo fictício para testar a descoberta de projetos: encontros de inclusão digital com início na data indicada.',
            'activity_date' => '2026-11-07 14:00:00',
            'activity_type' => ActivityType::Project,
            'capacity' => 25,
            'registration_deadline' => '2026-11-06',
        ]);
    }

    /**
     * Create a demo organization with no public contacts or social links.
     */
    private function makeOrganization(string $name, FocusArea $focusArea, string $description): Organization
    {
        return Organization::factory()->create([
            'name' => $name,
            'description' => $description,
            'focus_area' => $focusArea,
            'contact_email' => null,
            'contact_phone' => null,
            'social_media_links' => null,
        ]);
    }

    /**
     * Create a space owned by the given organization, at its own location.
     *
     * @param  list<string>  $facilities
     */
    private function makeSpace(Organization $organization, string $name, string $neighbourhood, int $capacity, array $facilities, string $description): Space
    {
        $location = Location::factory()->create([
            'neighbourhood' => $neighbourhood,
        ]);

        return Space::factory()->create([
            'name' => $name,
            'description' => $description,
            'capacity' => $capacity,
            'facilities' => $facilities,
            'location_id' => $location->id,
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * Create the given number of registrations for an activity so the public
     * "inscritos" count (withCount) reflects the intended availability state.
     */
    private function makeRegistrations(Activity $activity, int $count): void
    {
        if ($count < 1) {
            return;
        }

        Registration::factory()
            ->count($count)
            ->create([
                'activity_id' => $activity->id,
                'registered_at' => Carbon::parse('2026-10-01 12:00:00'),
            ]);
    }
}
