<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;
use App\Support\ViewPresenters;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Public directory of organizations (organizacoes.index).
     *
     * TODO (controller logic): fetch all organizations and map each with
     * ViewPresenters::organization(), returning them under 'organizacoes'.
     */
    public function index(): View
    {
        $organizations = Organization::all()->map(fn (Organization $organization): array => ViewPresenters::organization($organization))->all();

        return view('organizacoes.index', ['organizacoes' => $organizations]);
    }

    /**
     * Public profile for a single organization (organizacoes.show).
     *
     * TODO (controller logic): load the organization by id (404 if missing)
     * and pass:
     *   - 'organizacao'        => ViewPresenters::organization($organization)
     *   - 'eventosOrganizacao' => its activities mapped via ViewPresenters::activity
     *   - 'espacosOrganizacao' => its spaces mapped via ViewPresenters::space
     * Both related lists must be filtered by the organization's foreign keys
     * (organization_id), not by name.
     */
    public function show(int $id): View
    {
        $organization = Organization::query()->with(['spaces.location', 'ownedActivities.space.location', 'ownedActivities.organization'])->findOrFail($id);
        $eventosOrganizacao = $organization->ownedActivities
            ->map(fn (Activity $activity): array => ViewPresenters::activity($activity))
            ->all();

        $espacosOrganizacao = $organization->spaces
            ->map(fn (Space $space): array => ViewPresenters::space($space))
            ->all();

        return view('organizacoes.show', [
            'organizacao' => ViewPresenters::organization($organization),
            'eventosOrganizacao' => $eventosOrganizacao,
            'espacosOrganizacao' => $espacosOrganizacao,
        ]);
    }

    public function store(StoreOrganizationRequest $request): RedirectResponse
    {
        $organization = Organization::query()->create($request->validated());

        return redirect()
            ->route('organizacoes.show', $organization->id)
            ->with('success', 'Organização cadastrada com sucesso.');
    }
}
