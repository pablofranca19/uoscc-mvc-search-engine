<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceReservationRequest;
use App\Models\Space;
use Illuminate\Http\RedirectResponse;

class SpaceReservationController extends Controller
{
    /**
     * Handle a space reservation request (espacos.reserva).
     *
     * Field validation lives in StoreSpaceReservationRequest; this method
     * persists the request as a pending reservation (the owning organization
     * reviews availability afterwards) and confirms it via the 'success' flash.
     */
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
