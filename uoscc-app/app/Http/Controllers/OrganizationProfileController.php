<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrganizationProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless($user?->isOrganizationManager(), 403);

        $organization = $user->organization()
            ->withCount(['ownedActivities', 'spaces'])
            ->firstOrFail();

        return view('organizacoes.profile', [
            'organization' => $organization,
            'activities' => $organization->ownedActivities()
                ->with('space')
                ->orderByDesc('activity_date')
                ->get(),
        ]);
    }
}
