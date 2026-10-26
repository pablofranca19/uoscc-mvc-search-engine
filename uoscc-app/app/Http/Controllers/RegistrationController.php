<?php

namespace App\Http\Controllers;

use App\ActivityRegistration;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Support\ViewPresenters;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    /**
     * Handle an event registration (eventos.inscricao).
     *
     * Field validation lives in StoreRegistrationRequest; this method owns the
     * registration business rules and persistence:
     *   1. Load the activity by id (404 if missing).
     *   2. Reject the request when registration is not open (deadline passed or
     *      no spots left), redirecting back with an 'inscricao' error.
     *   3. Reject a duplicate registration (same e-mail for the same activity).
     *   4. Persist the registration and confirm it via the 'success' flash.
     */
    public function store(StoreRegistrationRequest $request, int $id): RedirectResponse
    {
        $activity = Activity::query()
            ->with(['organization', 'space.location'])
            ->withCount('registrations')
            ->findOrFail($id);

        $email = (string) $request->validated('email');

        $alreadyRegistered = $activity->registrations()
            ->where('participant_contact', $email)
            ->exists();


        if (ActivityRegistration::status(ViewPresenters::activity($activity)) !== 'aberta') {
            return redirect()
                ->route('eventos.show', $id)
                ->withErrors(['inscricao' => 'As inscrições desta atividade estão encerradas ou as vagas estão esgotadas.']);
        }

        if ($alreadyRegistered) {
            return redirect()
                ->route('eventos.show', $id)
                ->withErrors(['inscricao' => 'Este e-mail já está inscrito nesta atividade.']);
        }

        $activity->registrations()->create([
            'participant_name' => $request->validated('nome'),
            'participant_contact' => $email,
            'registered_at' => now(),
        ]);

        return redirect()
            ->route('eventos.show', $id)
            ->with('success', 'Inscrição confirmada! Sua participação foi registrada.');
    }
}
