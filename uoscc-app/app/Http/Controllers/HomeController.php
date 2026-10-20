<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;
use App\Support\ViewPresenters;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Landing page (home).
     *
     * TODO (controller logic): build the three catalogs the view consumes and
     * pass them as arrays of the shapes produced by App\Support\ViewPresenters:
     *   - 'eventos'      => mapped Activity list (ViewPresenters::activity)
     *   - 'organizacoes' => mapped Organization list (ViewPresenters::organization)
     *   - 'espacos'      => mapped Space list (ViewPresenters::space)
     * The home view only needs counts + a few featured cards, so the full
     * lists are sufficient.
     */
    public function index(): View
    {
        $activities = Activity::query()->with(['organization', 'space.location'])->withCount('registrations')->get()->map(fn (Activity $activity): array => ViewPresenters::activity($activity))->all();
        $organizations = Organization::all()->map(fn (Organization $organization): array => ViewPresenters::organization($organization))->all();
        $spaces = Space::query()->with(['organization', 'location'])->get()->map(fn (Space $space): array => ViewPresenters::space($space))->all();

        return view('home', [
            'eventos' => $activities,
            'organizacoes' => $organizations,
            'espacos' => $spaces,
        ]);
    }
}
