<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceReservationRequest;
use App\Models\Space;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpaceReservationController extends Controller
{
    /**
     * List every reservation made for a space, including its status
     * (espacos.reservas).
     */
    public function show(int $id): View
    {
        $space = Space::query()
            ->with(['reservations' => fn ($query) => $query->latest('requested_date')])
            ->findOrFail($id);

        return view('espacos.reservas', [
            'espaco' => $space,
            'reservas' => $space->reservations,
        ]);
    }

    public function store(StoreSpaceReservationRequest $request, int $id): RedirectResponse
    {
        $space = Space::query()->findOrFail($id);

        $space->reservations()->create([
            'requester_name' => $request->validated('nome'),
            'requester_contact' => $request->validated('email'),
            'requested_date' => $request->validated('data'),
            'purpose' => $request->validated('finalidade'),
        ]);

        return redirect()
            ->route('espacos.show', $id)
            ->with('success', 'Solicitação enviada! A organização responsável vai analisar a disponibilidade e entrar em contato.');
    }
}
