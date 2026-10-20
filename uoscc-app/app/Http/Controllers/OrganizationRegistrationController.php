<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationRegistrationController extends Controller
{
    public function create(): View
    {
        return view('organizacoes.create');
    }

    public function store(RegisterOrganizationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data): User {
            $organization = Organization::query()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'contact_email' => $data['contact_email'] ?? null,
                'contact_phone' => $data['contact_phone'] ?? null,
                'social_media_links' => $data['social_media_links'] ?? null,
                'focus_area' => $data['focus_area'],
            ]);

            return User::query()->create([
                'name' => $data['manager_name'],
                'email' => $data['manager_email'],
                'password' => Hash::make($data['password']),
                'organization_id' => $organization->id,
                'role' => UserRole::OrganizationManager,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('organizacoes.perfil')
            ->with('success', 'Organização cadastrada. Acesse seu perfil para publicar o primeiro evento ou projeto.');
    }
}
