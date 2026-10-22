<?php

namespace App\Http\Controllers;

use App\Models\SupporterApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupporterReviewController extends Controller
{
    public function index(): View
    {
        return view('apoiadores-admin', [
            'applications' => SupporterApplication::with(['user', 'reviewer'])->orderByDesc('submitted_at')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function update(Request $request, SupporterApplication $application): RedirectResponse
    {
        abort_if($application->user_id === $request->user()->id, 403, 'Outra pessoa da equipe deve analisar sua solicitação.');
        $data = $request->validate([
            'decision' => ['required', 'in:approved,changes_requested'],
            'review_note' => ['required_if:decision,changes_requested', 'nullable', 'string', 'min:5', 'max:1000'],
        ], [
            'decision.required' => 'Escolha uma decisão.',
            'decision.in' => 'Escolha aprovar ou solicitar ajustes.',
            'review_note.required_if' => 'Explique quais informações precisam ser ajustadas.',
            'review_note.string' => 'Informe uma orientação válida.',
            'review_note.min' => 'Use pelo menos 5 caracteres na orientação.',
            'review_note.max' => 'Use no máximo 1000 caracteres na orientação.',
        ]);
        $changed = SupporterApplication::whereKey($application->id)->where('status', 'pending')->update([
            'status' => $data['decision'],
            'review_note' => $data['review_note'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);
        abort_unless($changed === 1, 409, 'Esta solicitação já foi analisada. Atualize a página.');

        return redirect()->route('apoiadores.admin')->with('success', 'Análise registrada. O solicitante verá o resultado ao acessar sua conta.');
    }
}
