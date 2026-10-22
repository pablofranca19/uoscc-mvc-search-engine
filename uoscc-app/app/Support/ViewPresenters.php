<?php

namespace App\Support;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;

/**
 * Maps Eloquent models into the array shapes consumed by the public Blade
 * views and their components (x-event-card, x-org-card, x-space-card).
 *
 * Keeping the mapping in one place prevents drift between the controllers
 * that feed the same views (home, listing and detail pages).
 */
class ViewPresenters
{
    /**
     * Map an Activity into the shape used by the events views and card.
     *
     * The `inscritos` count is read from the `registrations_count` aggregate,
     * so callers should eager-load it with `withCount('registrations')`.
     *
     * @return array{
     *     id: int,
     *     natureza: string,
     *     titulo: string,
     *     organizacao: string,
     *     data: string,
     *     hora: string,
     *     local: string,
     *     regiao: string,
     *     tipo: string,
     *     vagas: int,
     *     inscritos: int,
     *     descricao: string,
     *     prazo_inscricao: string
     * }
     */
    public static function activity(Activity $activity): array
    {
        $deadline = $activity->registration_deadline ?? $activity->activity_date;

        return [
            'id' => $activity->id,
            'natureza' => $activity->activity_type === ActivityType::Project ? 'projeto' : 'evento',
            'titulo' => $activity->title,
            'organizacao' => $activity->organization?->name ?? '',
            'data' => $activity->activity_date->toDateString(),
            'hora' => $activity->activity_date->format('H:i'),
            'local' => $activity->space?->name ?? '',
            'regiao' => $activity->space?->location?->neighbourhood ?? '',
            'tipo' => $activity->activity_type->value,
            'vagas' => (int) ($activity->capacity ?? 0),
            'inscritos' => (int) ($activity->registrations_count ?? $activity->registrations()->count()),
            'descricao' => $activity->description,
            'prazo_inscricao' => $deadline->toDateString(),
        ];
    }

    /**
     * Map an Organization into the shape used by the organization views and card.
     *
     * @return array{
     *     id: int,
     *     nome: string,
     *     sigla: string,
     *     area: string,
     *     descricao: string,
     *     email: ?string,
     *     telefone: ?string,
     *     redes_sociais: array<string, string>
     * }
     */
    public static function organization(Organization $organization): array
    {
        return [
            'id' => $organization->id,
            'nome' => $organization->name,
            'sigla' => self::acronym($organization->name),
            'area' => $organization->focus_area->label(),
            'descricao' => $organization->description,
            'email' => $organization->contact_email,
            'telefone' => $organization->contact_phone,
            'redes_sociais' => self::socialLinks($organization),
        ];
    }

    /**
     * Build a short uppercase acronym (max 3 letters) from an organization name.
     *
     * Uses the initials of the first words, falling back to the first letters
     * of the name when it is a single word.
     */
    private static function acronym(string $name): string
    {
        $words = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if (count($words) >= 2) {
            $initials = array_map(static fn (string $word): string => mb_substr($word, 0, 1), $words);

            return mb_strtoupper(implode('', array_slice($initials, 0, 3)));
        }

        return mb_strtoupper(mb_substr($name, 0, 3));
    }

    /**
     * Map a Space into the shape used by the spaces views and card.
     *
     * @return array{
     *     id: int,
     *     nome: string,
     *     organizacao: string,
     *     capacidade: int,
     *     regiao: string,
     *     estrutura: list<string>,
     *     descricao: string
     * }
     */
    public static function space(Space $space): array
    {
        return [
            'id' => $space->id,
            'nome' => $space->name,
            'organizacao' => $space->organization?->name ?? '',
            'capacidade' => (int) $space->capacity,
            'regiao' => $space->location?->neighbourhood ?? '',
            'estrutura' => array_values($space->facilities ?? []),
            'descricao' => $space->description,
        ];
    }

    /**
     * Normalize an organization's social links into a label => url map.
     *
     * @return array<string, string>
     */
    private static function socialLinks(Organization $organization): array
    {
        $raw = $organization->social_media_links;

        if ($raw === null || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (is_array($decoded)) {
            /** @var array<string, string> $decoded */
            return $decoded;
        }

        return ['Site' => $raw];
    }
}
