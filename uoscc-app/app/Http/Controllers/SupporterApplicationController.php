<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitSupporterApplicationRequest;
use App\Models\SupporterApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupporterApplicationController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasApprovedSupporterAccess()) {
            return redirect()->route('apoiadores.painel');
        }

        return view('apoiadores-solicitacao', [
            'application' => $request->user()->supporterApplication,
        ]);
    }

    public function store(SubmitSupporterApplicationRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $user = $request->user()->newQuery()->lockForUpdate()->findOrFail($request->user()->id);
            $application = $user->supporterApplication()->first();
            abort_if($application && $application->status !== 'changes_requested', 409, 'Sua solicitação já está em análise ou foi aprovada.');
            $application ??= new SupporterApplication;
            $application->fill($request->safe()->only(['applicant_type', 'organization_name', 'interests', 'contribution']));
            $application->organization_name = $request->input('applicant_type') === 'organization' ? $request->validated('organization_name') : null;
            $application->user_id = $user->id;
            $application->status = 'pending';
            $application->submitted_at = now();
            $application->review_note = null;
            $application->reviewed_by = null;
            $application->reviewed_at = null;
            $application->save();
        });

        return redirect()->route('apoiadores.solicitacao')->with('success', 'Solicitação enviada. Acompanhe a análise nesta página.');
    }
}
