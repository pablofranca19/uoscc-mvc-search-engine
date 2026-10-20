<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Models\Activity;
use App\Support\ViewPresenters;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function create(Request $request): View
    {
        abort_unless($request->user()->isOrganizationManager(), 403);

        return view('eventos.create', [
            'spaces' => $request->user()->organization?->spaces()->orderBy('name')->get() ?? collect(),
        ]);
    }

    /**
     * Public listing of events and projects (eventos.index).
     */
    public function index(): View
    {
        $eventos = Activity::query()
            ->public()
            ->with(['organization', 'space.location'])
            ->withCount('registrations')
            ->get()
            ->map(fn (Activity $activity): array => ViewPresenters::activity($activity))
            ->all();

        return view('eventos.index', ['eventos' => $eventos]);
    }

    /**
     * Public detail page for a single activity (eventos.show).
     *
     * TODO (controller logic): load the activity by id (404 if missing),
     * eager-load organization + space.location + registrations count, map it
     * with ViewPresenters::activity() and return the 'eventos.show' view with
     * an 'evento' key.
     */
    public function show(int $id): View
    {
        $activity = Activity::query()
            ->with(['organization', 'space.location'])
            ->withCount('registrations')
            ->findOrFail($id);

        return view('eventos.show', ['evento' => ViewPresenters::activity($activity)]);
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $activity = Activity::query()->create([
            ...$request->validated(),
            'organization_id' => $request->user()->organization_id,
        ]);

        return redirect()
            ->route('eventos.show', $activity->id)
            ->with('success', 'Atividade cadastrada com sucesso.');
    }
}
