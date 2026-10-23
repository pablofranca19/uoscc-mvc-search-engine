<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\SupporterFavorite;
use App\Models\User;
use App\Support\ActivityRegistration;
use App\Support\ViewPresenters;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Supporter area: profile, favorites, simplified registration, participation
 * confirmation and certificates. Every read and write goes to the database:
 * the dashboard owns no in-memory catalog and keeps no state in the session.
 */
class SupporterDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profile($user);
        $favorites = $this->favorites($user);

        return view('apoiadores.dashboard', [
            'activities' => $this->openCatalog(),
            'organizations' => $this->organizationCatalog(),
            'profile' => $profile,
            'favorites' => $favorites,
            'registrations' => $this->registrations($user),
            'notifications' => $this->notifications($favorites, $profile),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $profile = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:254', Rule::unique('users', 'email')->ignore($request->user())],
            'skills' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'string', 'max:1000'],
            'availability' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->update([
            'name' => $profile['name'],
            'email' => $profile['email'],
            'supporter_skills' => $profile['skills'],
            'supporter_interests' => $profile['interests'],
            'supporter_availability' => $profile['availability'],
        ]);

        return redirect()->route('apoiadores')->with('success', 'Perfil de apoiador atualizado. Suas próximas inscrições poderão usar estes dados.');
    }

    public function toggleFavorite(Request $request, string $type, string $id): RedirectResponse
    {
        $favoritable = $this->favoritable($type, $id);
        abort_unless($favoritable !== null, 404);

        $favorite = $request->user()->favorites()
            ->where('favoritable_type', $favoritable->getMorphClass())
            ->where('favoritable_id', $favoritable->getKey())
            ->first();

        if ($favorite instanceof SupporterFavorite) {
            $favorite->delete();

            return redirect()->route('apoiadores')->with('success', 'Item removido dos favoritos.');
        }

        $request->user()->favorites()->create([
            'favoritable_type' => $favoritable->getMorphClass(),
            'favoritable_id' => $favoritable->getKey(),
        ]);

        return redirect()->route('apoiadores')->with('success', 'Item salvo nos seus favoritos.');
    }

    /**
     * Register the authenticated supporter for an activity reusing the contact
     * data already stored in their profile.
     */
    public function register(Request $request, string $type, string $id): RedirectResponse
    {
        $activity = $this->activity($type, $id);
        abort_unless($activity !== null, 404);

        $user = $request->user();
        $profile = $this->profile($user);

        if ($profile['name'] === '' || $profile['email'] === '') {
            return redirect()->route('apoiadores')->with('success', 'Complete seu perfil para usar a inscrição simplificada.');
        }

        if ($user->registrations()->where('activity_id', $activity->id)->exists()) {
            return redirect()->route('apoiadores')->with('success', 'Você já está inscrito nesta atividade.');
        }

        if ($this->acceptsRegistrations($activity) === false) {
            return redirect()->route('apoiadores')->withErrors([
                'inscricao' => 'As inscrições desta atividade estão encerradas ou as vagas estão esgotadas.',
            ]);
        }

        $activity->registrations()->create([
            'user_id' => $user->id,
            'participant_name' => $profile['name'],
            'participant_contact' => $profile['email'],
            'registered_at' => now(),
        ]);

        return redirect()->route('apoiadores')->with('success', 'Inscrição confirmada sem repetir seus dados de contato.');
    }

    public function confirmParticipation(Request $request, string $type, string $id): RedirectResponse
    {
        $registration = $this->registration($request->user(), $type, $id);
        abort_unless($registration !== null, 404);

        $registration->update(['completed_at' => now()]);

        return redirect()->route('apoiadores')->with('success', 'Participação registrada. Seu certificado já está disponível.');
    }

    public function certificate(Request $request, string $type, string $id): View
    {
        $registration = $this->registration($request->user(), $type, $id);
        abort_unless($registration?->isParticipationConfirmed() === true, 404);

        return view('apoiadores.certificate', [
            'profile' => $this->profile($request->user()),
            'registration' => $this->presentRegistration($registration),
        ]);
    }

    /**
     * Activities with open registration, keyed by "{type}:{id}".
     *
     * @return array<string, array{id: int, type: string, title: string, organization: string, date: string, area: string}>
     */
    private function openCatalog(): array
    {
        return Activity::query()
            ->public()
            ->with(['organization', 'space.location'])
            ->withCount('registrations')
            ->get()
            ->filter(fn (Activity $activity): bool => $this->acceptsRegistrations($activity))
            ->mapWithKeys(fn (Activity $activity): array => [
                $this->activityKey($activity) => $this->presentActivity($activity),
            ])
            ->all();
    }

    /**
     * Organizations offered on the dashboard, keyed by "osc:{id}".
     *
     * @return array<string, array{id: int, name: string, area: string}>
     */
    private function organizationCatalog(): array
    {
        return Organization::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Organization $organization): array => [
                'osc:'.$organization->id => $this->presentOrganization($organization),
            ])
            ->all();
    }

    /**
     * The supporter's saved favorites, keyed the same way as the catalogs.
     *
     * @return array<string, array<string, int|string>>
     */
    private function favorites(User $user): array
    {
        return $user->favorites()
            ->with(['favoritable' => fn (MorphTo $favoritable) => $favoritable->morphWith([
                Activity::class => ['organization'],
            ])])
            ->latest()
            ->get()
            ->reject(fn (SupporterFavorite $favorite): bool => $favorite->favoritable === null)
            ->mapWithKeys(function (SupporterFavorite $favorite): array {
                $favoritable = $favorite->favoritable;

                return $favoritable instanceof Activity
                    ? [$this->activityKey($favoritable) => $this->presentActivity($favoritable)]
                    : ['osc:'.$favoritable->getKey() => $this->presentOrganization($favoritable)];
            })
            ->all();
    }

    /**
     * The supporter's registrations, keyed the same way as the catalogs.
     *
     * @return array<string, array{id: int, type: string, title: string, organization: string, date: string, area: string, status: string, registered_at: string, completed_at: ?string}>
     */
    private function registrations(User $user): array
    {
        return $user->registrations()
            ->with('activity.organization')
            ->get()
            ->filter(fn (Registration $registration): bool => $registration->activity !== null)
            ->mapWithKeys(fn (Registration $registration): array => [
                $this->activityKey($registration->activity) => $this->presentRegistration($registration),
            ])
            ->all();
    }

    /**
     * Load one of the supporter's registrations by activity type and id.
     */
    private function registration(User $user, string $type, string $id): ?Registration
    {
        $activity = $this->activity($type, $id);

        if ($activity === null) {
            return null;
        }

        return $user->registrations()
            ->with('activity.organization')
            ->where('activity_id', $activity->id)
            ->first();
    }

    /**
     * Load an activity by its dashboard type ("evento"/"projeto") and id.
     */
    private function activity(string $type, string $id): ?Activity
    {
        $activityType = match ($type) {
            'evento' => ActivityType::Event,
            'projeto' => ActivityType::Project,
            default => null,
        };

        if ($activityType === null) {
            return null;
        }

        return Activity::query()
            ->with(['organization', 'space.location'])
            ->withCount('registrations')
            ->ofType($activityType)
            ->find($id);
    }

    /**
     * Load the record a favorite may point at: an organization or an activity.
     */
    private function favoritable(string $type, string $id): ?Model
    {
        return $type === 'osc'
            ? Organization::query()->find($id)
            : $this->activity($type, $id);
    }

    /**
     * Whether an activity still accepts registrations, using the same rules as
     * the public activity pages (deadline in Bahia time and remaining spots).
     */
    private function acceptsRegistrations(Activity $activity): bool
    {
        return ActivityRegistration::status(ViewPresenters::activity($activity)) === 'aberta';
    }

    /**
     * Dashboard key for an activity: "evento:{id}" or "projeto:{id}".
     */
    private function activityKey(Activity $activity): string
    {
        $type = $activity->activity_type === ActivityType::Project ? 'projeto' : 'evento';

        return $type.':'.$activity->id;
    }

    /**
     * @return array{id: int, type: string, title: string, organization: string, date: string, area: string}
     */
    private function presentActivity(Activity $activity): array
    {
        return [
            'id' => $activity->id,
            'type' => $activity->activity_type === ActivityType::Project ? 'Projeto' : 'Evento',
            'title' => $activity->title,
            'organization' => $activity->organization?->name ?? '',
            'date' => $activity->activity_date->toDateString(),
            'area' => $activity->organization?->focus_area->label() ?? '',
        ];
    }

    /**
     * @return array{id: int, name: string, area: string}
     */
    private function presentOrganization(Organization $organization): array
    {
        return [
            'id' => $organization->id,
            'name' => $organization->name,
            'area' => $organization->focus_area->label(),
        ];
    }

    /**
     * @return array{id: int, type: string, title: string, organization: string, date: string, area: string, status: string, registered_at: string, completed_at: ?string}
     */
    private function presentRegistration(Registration $registration): array
    {
        return [
            ...$this->presentActivity($registration->activity),
            'status' => $registration->statusLabel(),
            'registered_at' => $registration->registered_at->timezone('America/Bahia')->toDateTimeString(),
            'completed_at' => $registration->completed_at?->timezone('America/Bahia')->toDateString(),
        ];
    }

    /**
     * @return array{name: string, email: string, skills: string, interests: string, availability: string}
     */
    private function profile(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'skills' => $user->supporter_skills ?? '',
            'interests' => $user->supporter_interests ?? '',
            'availability' => $user->supporter_availability ?? '',
        ];
    }

    /**
     * @param  array<string, array<string, int|string>>  $favorites
     * @param  array{name: string, email: string, skills: string, interests: string, availability: string}  $profile
     * @return list<string>
     */
    private function notifications(array $favorites, array $profile): array
    {
        $notifications = [];

        foreach ($favorites as $favorite) {
            $name = $favorite['name'] ?? $favorite['title'];
            $notifications[] = 'Há novidades em '.$name.'. Consulte as oportunidades disponíveis.';
        }

        if ($profile['interests'] !== '') {
            $notifications[] = 'Encontramos oportunidades compatíveis com seus interesses em voluntariado.';
        }

        return $notifications;
    }
}
