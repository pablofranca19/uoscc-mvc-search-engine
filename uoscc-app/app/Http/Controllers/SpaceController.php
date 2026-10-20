<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Models\Space;
use App\Support\ViewPresenters;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpaceController extends Controller
{
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
        $space = Space::query()->create([
            ...$request->validated(),
            'organization_id' => $request->user()->organization_id,
        ]);

        return redirect()
            ->route('espacos.show', $space->id)
            ->with('success', 'Espaço cadastrado com sucesso.');
    }
}
