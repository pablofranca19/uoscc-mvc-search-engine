<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Models\Location;
use App\Models\Space;
use App\Support\ViewPresenters;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SpaceController extends Controller
{
    public function create(Request $request): View
    {
        abort_unless($request->user()?->isOrganizationManager(), 403);

        return view('espacos.create');
    }

    /**
     * Public listing of rentable spaces (espacos.index).
     */
    public function index(): View
    {
        $espacos = Space::query()
            ->with(['organization', 'location'])
            ->get()
            ->map(fn (Space $space): array => ViewPresenters::space($space))
            ->all();

        return view('espacos.index', ['espacos' => $espacos]);
    }

    /**
     * Public detail page for a single space (espacos.show).
     */
    public function show(int $id): View
    {
        $space = Space::query()
            ->with(['organization', 'location'])
            ->findOrFail($id);

        return view('espacos.show', ['espaco' => ViewPresenters::space($space)]);
    }

    public function store(StoreSpaceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request): void {
            $locationId = $data['location_id'] ?? Location::query()->create([
                'cep' => $data['cep'],
                'neighbourhood' => $data['neighbourhood'],
                'street' => $data['street'],
                'number' => $data['number'],
            ])->id;

            Space::query()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'capacity' => $data['capacity'],
                'facilities' => $data['facilities'] ?? [],
                'location_id' => $locationId,
                'organization_id' => $request->user()->organization_id,
            ]);
        });

        return redirect()
            ->route('organizacoes.perfil')
            ->with('success', 'Espaço cadastrado com sucesso. Ele já pode ser associado aos eventos da OSC.');
    }
}
