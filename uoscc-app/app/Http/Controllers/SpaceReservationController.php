<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Http\Requests\StoreSpaceReservationRequest;
use App\Models\Space;
use App\Models\SpaceReservation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SpaceReservationController extends Controller
{
    /**
     * Reservation panel for the organization that owns the space
     * (espacos.reservas). Requests carry the requester's contact data, so only
     * the responsible organization may read them.
     */
    public function show(Request $request, int $id): View
    {
        $space = $this->manageableSpace($request, $id);

        return view('espacos.reservas', [
            'espaco' => $space,
            'reservas' => $space->reservations,
        ]);
    }

    public function store(StoreSpaceReservationRequest $request, int $id): RedirectResponse
    {
        $space = Space::query()->findOrFail($id);
        $requestedDate = (string) $request->validated('data');

        if (! $space->isAvailableOn($requestedDate)) {
            return redirect()
                ->route('espacos.show', $id)
                ->withErrors(['data' => 'Esta data já possui uma reserva aprovada. Escolha outra data.'])
                ->withInput();
        }

        $space->reservations()->create([
            'requester_name' => $request->validated('nome'),
            'requester_contact' => $request->validated('email'),
            'requested_date' => $requestedDate,
            'purpose' => $request->validated('finalidade'),
        ]);

        return redirect()
            ->route('espacos.show', $id)
            ->with('success', 'Solicitação enviada! A organização responsável vai analisar a disponibilidade e entrar em contato.');
    }

    /**
     * Approve a pending request, unless the date was already granted to
     * someone else. The check and the write share a transaction so two
     * managers approving at the same time cannot both win the date.
     */
    public function approve(Request $request, int $id, int $reservation): RedirectResponse
    {
        $space = $this->manageableSpace($request, $id);
        $pendingReservation = $this->pendingReservation($space, $reservation);

        if ($pendingReservation === null) {
            return $this->decidedRedirect($id);
        }

        $approved = DB::transaction(function () use ($space, $pendingReservation): bool {
            if (! $space->isAvailableOn($pendingReservation->requested_date->toDateString(), lockForUpdate: true)) {
                return false;
            }

            $pendingReservation->approveRequest();

            return true;
        });

        if (! $approved) {
            return redirect()
                ->route('espacos.reservas', $id)
                ->withErrors(['reserva' => 'Já existe uma reserva aprovada para esta data. Recuse esta solicitação ou cancele a reserva aprovada.']);
        }

        return redirect()
            ->route('espacos.reservas', $id)
            ->with('success', 'Reserva aprovada. Entre em contato com a pessoa solicitante para combinar os detalhes.');
    }

    /**
     * Refuse a pending request, leaving the date open to other requests.
     */
    public function reject(Request $request, int $id, int $reservation): RedirectResponse
    {
        $space = $this->manageableSpace($request, $id);
        $pendingReservation = $this->pendingReservation($space, $reservation);

        if ($pendingReservation === null) {
            return $this->decidedRedirect($id);
        }

        $pendingReservation->rejectRequest();

        return redirect()
            ->route('espacos.reservas', $id)
            ->with('success', 'Solicitação recusada. A data segue disponível para outras solicitações.');
    }

    /**
     * Load a space the authenticated user is responsible for, with its
     * reservations, or fail with 404 (unknown space) / 403 (not the owner).
     */
    private function manageableSpace(Request $request, int $id): Space
    {
        $space = Space::query()
            ->with([
                'organization',
                'location',
                'reservations' => fn (HasMany $query) => $query->latest('requested_date'),
            ])
            ->findOrFail($id);

        abort_unless($request->user()?->canManage($space->organization) === true, 403);

        return $space;
    }

    /**
     * Load a pending reservation of the given space. Returns null when the
     * request was already approved or rejected.
     */
    private function pendingReservation(Space $space, int $reservation): ?SpaceReservation
    {
        $spaceReservation = $space->reservations()->findOrFail($reservation);

        return $spaceReservation->status === ReservationStatus::Pending
            ? $spaceReservation
            : null;
    }

    private function decidedRedirect(int $id): RedirectResponse
    {
        return redirect()
            ->route('espacos.reservas', $id)
            ->withErrors(['reserva' => 'Esta solicitação já foi analisada.']);
    }
}
