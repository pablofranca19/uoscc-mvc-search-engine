@extends('layouts.app')
@section('title', 'Análise de apoiadores — UOSCC')
@section('content')
<section class="section-shell py-10">
    <x-supporter-nav />
    <p class="eyebrow">Área da equipe</p><h1 class="section-title">Solicitações de apoiadores</h1>
    <p class="section-copy">Analise as informações antes de aprovar o acesso. Aprovar um apoiador não concede permissão para administrar a rede. A orientação registrada fica visível para o solicitante.</p>
    <div class="mt-8 space-y-6">
        @forelse($applications as $application)
            <article class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-wrap justify-between gap-3"><h2 class="text-xl font-black">{{ $application->user->name }}</h2><span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">{{ $application->statusLabel() }}</span></div>
                <p class="mt-2 text-sm">{{ $application->user->email }} · {{ $application->submitted_at->timezone('America/Bahia')->format('d/m/Y H:i') }} (Bahia)</p>
                <dl class="mt-5 grid gap-5 text-sm sm:grid-cols-2">
                    <div><dt class="font-bold">Participação</dt><dd class="mt-2">{{ $application->applicant_type === 'person' ? 'Pessoa' : 'Organização: '.$application->organization_name }}</dd></div>
                    <div><dt class="font-bold">Interesses</dt><dd class="mt-2 whitespace-pre-line">{{ $application->interests }}</dd></div>
                    <div class="sm:col-span-2"><dt class="font-bold">Como pretende contribuir</dt><dd class="mt-2 whitespace-pre-line">{{ $application->contribution }}</dd></div>
                </dl>
                @if($application->status === 'pending' && $application->user_id !== auth()->id())
                    <form action="{{ route('apoiadores.admin.review', $application) }}" method="POST" class="mt-6 space-y-4 border-t border-slate-200 pt-5 dark:border-slate-800">
                        @csrf @method('PATCH')
                        <label for="note-{{ $application->id }}" class="label">Orientação ao solicitante (obrigatória ao solicitar ajustes)</label>
                        <textarea id="note-{{ $application->id }}" name="review_note" class="input" rows="3" minlength="5" maxlength="1000">{{ old('review_note') }}</textarea>
                        <div class="flex flex-wrap gap-3"><button name="decision" value="approved" class="rounded-xl bg-uoscc-primary px-5 py-3 font-bold text-white hover:bg-uoscc-dark">Aprovar acesso</button><button name="decision" value="changes_requested" class="rounded-xl border border-slate-300 px-5 py-3 font-bold dark:border-slate-600">Solicitar ajustes</button></div>
                    </form>
                @elseif($application->status === 'pending')
                    <p class="mt-5 text-sm">Outra pessoa da equipe deve analisar sua própria solicitação.</p>
                @else
                    <p class="mt-5 text-sm">Análise registrada em {{ $application->reviewed_at?->timezone('America/Bahia')->format('d/m/Y H:i') }} por {{ $application->reviewer?->name ?? 'membro da equipe' }}.</p>
                    @if($application->review_note)<p class="mt-2 whitespace-pre-line text-sm">{{ $application->review_note }}</p>@endif
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 p-8 dark:border-slate-700"><h2 class="text-lg font-bold">Nenhuma solicitação recebida</h2><p class="mt-2 text-sm">As solicitações enviadas pelos usuários aparecerão aqui.</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</section>
@endsection
