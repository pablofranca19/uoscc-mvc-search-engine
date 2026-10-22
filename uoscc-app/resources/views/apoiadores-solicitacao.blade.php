@extends('layouts.app')
@section('title', 'Minha solicitação — UOSCC')
@section('content')
<section class="section-shell max-w-4xl py-10">
    <x-supporter-nav />
    <p class="eyebrow">Seu caminho na rede</p>
    <h1 class="section-title">{{ $application ? $application->statusLabel() : 'Conte como pretende participar' }}</h1>
    <p class="section-copy">Olá, {{ auth()->user()->name }}. Acompanhe sua solicitação por aqui. O resultado aparece nesta página; não há envio de e-mail configurado.</p>
    @if($application)
        <div class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 p-5 text-sm leading-7 text-sky-950 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-100">
            <p class="font-bold">{{ $application->statusLabel() }}</p>
            <p>Enviada em {{ $application->submitted_at->timezone('America/Bahia')->format('d/m/Y H:i') }} (Bahia).</p>
            @if($application->review_note)<p class="mt-3 whitespace-pre-line"><strong>Orientação da equipe:</strong> {{ $application->review_note }}</p>@endif
            @if($application->status === 'pending')<p class="mt-3">A equipe ainda precisa analisar suas informações. O painel exclusivo permanece bloqueado até a aprovação.</p><a href="{{ route('apoiadores.acesso') }}" class="mt-3 inline-block font-bold underline underline-offset-4">Consultar status novamente</a>@endif
        </div>
    @endif
    @if(!$application || $application->status === 'changes_requested')
        <form action="{{ route('apoiadores.solicitacao.store') }}" method="POST" class="mt-8 space-y-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
            @csrf
            <div><label for="applicant_type" class="label">Como pretende participar?</label><select id="applicant_type" name="applicant_type" class="input" required aria-invalid="{{ $errors->has('applicant_type') ? 'true' : 'false' }}" @if($errors->has('applicant_type')) aria-describedby="applicant-type-error" @endif><option value="">Selecione</option><option value="person" @selected(old('applicant_type', $application?->applicant_type) === 'person')>Como pessoa</option><option value="organization" @selected(old('applicant_type', $application?->applicant_type) === 'organization')>Representando uma organização</option></select>@error('applicant_type')<p id="applicant-type-error" class="mt-2 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>@enderror</div>
            <x-supporter-field name="organization_name" label="Organização que representa (obrigatório ao representar uma organização)" :required="false" :value="$application?->organization_name" maxlength="160" />
            @foreach(['interests' => ['Quais são suas áreas de interesse?', 500, 3], 'contribution' => ['Como gostaria de contribuir?', 2000, 20]] as $field => [$label, $max, $min])
                <div><label for="{{ $field }}" class="label">{{ $label }}</label><textarea id="{{ $field }}" name="{{ $field }}" rows="4" class="input" required minlength="{{ $min }}" maxlength="{{ $max }}" aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" aria-describedby="{{ $field }}-help @if($errors->has($field)){{ $field }}-error @endif">{{ old($field, $application?->{$field}) }}</textarea><p id="{{ $field }}-help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Entre {{ $min }} e {{ $max }} caracteres. Evite dados pessoais de terceiros.</p>@error($field)<p id="{{ $field }}-error" class="mt-2 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>@enderror</div>
            @endforeach
            <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">A equipe autorizada poderá ler estas informações para analisar seu acesso. O envio não garante aprovação nem reserva vagas em atividades.</p>
            <button class="rounded-xl bg-uoscc-primary px-6 py-3 font-bold text-white hover:bg-uoscc-dark">{{ $application ? 'Reenviar para análise' : 'Enviar solicitação' }}</button>
        </form>
    @else
        <dl class="mt-8 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 text-sm dark:border-slate-800 dark:bg-slate-900">
            <div><dt class="font-bold">Participação</dt><dd class="mt-2">{{ $application->applicant_type === 'person' ? 'Pessoa' : 'Organização: '.$application->organization_name }}</dd></div>
            <div><dt class="font-bold">Interesses</dt><dd class="mt-2 whitespace-pre-line">{{ $application->interests }}</dd></div>
            <div><dt class="font-bold">Contribuição proposta</dt><dd class="mt-2 whitespace-pre-line">{{ $application->contribution }}</dd></div>
        </dl>
    @endif
</section>
@endsection
