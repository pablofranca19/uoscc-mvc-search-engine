<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class SupporterDashboardController extends Controller
{
    /**
     * @return array<string, array{id: string, type: string, title: string, organization: string, date: string, area: string}>
     */
    private function activities(): array
    {
        return [
            'evento:1' => ['id' => '1', 'type' => 'Evento', 'title' => 'Feira de Cidadania e Serviços', 'organization' => 'Instituto Viver Melhor', 'date' => '2026-10-24', 'area' => 'Cidadania'],
            'evento:2' => ['id' => '2', 'type' => 'Evento', 'title' => 'Oficina de Inclusão Digital', 'organization' => 'Conecta Comunidade', 'date' => '2026-10-29', 'area' => 'Tecnologia'],
            'projeto:5' => ['id' => '5', 'type' => 'Projeto', 'title' => 'Trilhas Digitais', 'organization' => 'Conecta Comunidade', 'date' => '2026-11-07', 'area' => 'Tecnologia'],
        ];
    }

    /**
     * @return array<string, array{id: string, name: string, area: string}>
     */
    private function organizations(): array
    {
        return [
            'osc:1' => ['id' => '1', 'name' => 'Instituto Viver Melhor', 'area' => 'Assistência social'],
            'osc:2' => ['id' => '2', 'name' => 'Conecta Comunidade', 'area' => 'Educação e tecnologia'],
            'osc:3' => ['id' => '3', 'name' => 'Rede Verde Camaçari', 'area' => 'Meio ambiente'],
        ];
    }

    public function index(Request $request): View
    {
        $activities = $this->activities();
        $profile = $this->profile($request->user());
        $favorites = $request->session()->get('supporter.favorites', []);
        $registrations = $request->session()->get('supporter.registrations', []);

        return view('apoiadores.dashboard', [
            'activities' => $activities,
            'organizations' => $this->organizations(),
            'profile' => $profile,
            'favorites' => $favorites,
            'registrations' => $registrations,
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
        $item = $this->favoriteItem($type, $id);
        abort_unless($item !== null, 404);

        $favorites = $request->session()->get('supporter.favorites', []);
        $key = $type.':'.$id;

        if (isset($favorites[$key])) {
            unset($favorites[$key]);
            $message = 'Item removido dos favoritos.';
        } else {
            $favorites[$key] = $item;
            $message = 'Item salvo nos seus favoritos.';
        }

        $request->session()->put('supporter.favorites', $favorites);

        return redirect()->route('apoiadores')->with('success', $message);
    }

    public function register(Request $request, string $type, string $id): RedirectResponse
    {
        $activity = $this->activities()[$type.':'.$id] ?? null;
        abort_unless($activity !== null, 404);

        $profile = $this->profile($request->user());
        if ($profile['name'] === '' || $profile['email'] === '') {
            return redirect()->route('apoiadores')->with('success', 'Complete seu perfil para usar a inscrição simplificada.');
        }

        $registrations = $request->session()->get('supporter.registrations', []);
        $registrations[$type.':'.$id] ??= [
            ...$activity,
            'status' => 'Inscrição confirmada',
            'registered_at' => Carbon::now('America/Bahia')->toDateTimeString(),
        ];
        $request->session()->put('supporter.registrations', $registrations);

        return redirect()->route('apoiadores')->with('success', 'Inscrição confirmada sem repetir seus dados de contato.');
    }

    public function confirmParticipation(Request $request, string $type, string $id): RedirectResponse
    {
        $key = $type.':'.$id;
        $registrations = $request->session()->get('supporter.registrations', []);
        abort_unless(isset($registrations[$key]), 404);

        $registrations[$key]['status'] = 'Participação confirmada';
        $registrations[$key]['completed_at'] = Carbon::now('America/Bahia')->toDateString();
        $request->session()->put('supporter.registrations', $registrations);

        return redirect()->route('apoiadores')->with('success', 'Participação registrada. Seu certificado já está disponível.');
    }

    public function certificate(Request $request, string $type, string $id): View
    {
        $registration = $request->session()->get('supporter.registrations.'.$type.':'.$id);
        abort_unless(is_array($registration) && isset($registration['completed_at']), 404);

        return view('apoiadores.certificate', [
            'profile' => $this->profile($request->user()),
            'registration' => $registration,
        ]);
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
     * @return array{id: string, name: string, area: string}|array{id: string, type: string, title: string, organization: string, date: string, area: string}|null
     */
    private function favoriteItem(string $type, string $id): ?array
    {
        return match ($type) {
            'osc' => $this->organizations()['osc:'.$id] ?? null,
            'evento', 'projeto' => $this->activities()[$type.':'.$id] ?? null,
            default => null,
        };
    }

    /**
     * @param  array<string, array<string, string>>  $favorites
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
