@extends('layouts.app')
@section('title', $espaco['nome'].' — UOSCC')
@section('content')
<section class="bg-emerald-50 py-14 text-slate-900 dark:bg-slate-950 dark:text-white sm:py-20">
    <div class="section-shell">
        <a href="{{ route('espacos') }}" class="text-sm font-bold text-uoscc-primary dark:text-emerald-300">← Voltar para espaços</a>
        <p class="mt-8 text-sm font-bold text-uoscc-primary dark:text-emerald-300">{{ $espaco['organizacao'] }}</p>
        <h1 class="mt-3 text-3xl font-black sm:text-5xl">{{ $espaco['nome'] }}</h1>
        <p class="mt-4 max-w-2xl leading-7 text-slate-600 dark:text-slate-300">{{ $espaco['descricao'] }}</p>
    </div>
</section>
<section class="section-shell grid items-start gap-8 py-12 lg:grid-cols-[1fr_380px]">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white">Estrutura disponível</h2>
        <ul class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach($espaco['estrutura'] as $item)
                <li class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800 dark:bg-emerald-400/10 dark:text-emerald-200"><span aria-hidden="true">✓</span> {{ $item }}</li>
            @endforeach
        </ul>
        <dl class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800"><dt class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Capacidade</dt><dd class="mt-2 font-black text-slate-900 dark:text-white">{{ $espaco['capacidade'] }} pessoas</dd></div>
            <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800"><dt class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Região</dt><dd class="mt-2 font-black text-slate-900 dark:text-white">{{ $espaco['regiao'] }}</dd></div>
        </dl>
        <div class="mt-8 rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
            <h2 class="font-bold text-slate-900 dark:text-white">Como será a solicitação?</h2>
            <ol class="mt-4 list-inside list-decimal space-y-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                <li>Informe a data desejada e a finalidade.</li>
                <li>A OSC responsável analisa a solicitação e aprova ou recusa a data.</li>
                <li>Aguarde uma confirmação antes de considerar o espaço reservado.</li>
            </ol>
        </div>
    </div>
    <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg sm:p-7 dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/40">
        <p class="text-xs font-black uppercase tracking-wider text-uoscc-primary">Solicitação de espaço</p>
        <h2 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Planeje sua atividade</h2>
        <p id="reservation-help" class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-900 dark:border-emerald-500/30 dark:bg-emerald-400/10 dark:text-emerald-100">Preencha os dados abaixo para enviar sua solicitação. A organização responsável vai analisar a disponibilidade da data escolhida.</p>
        <form action="{{ route('espacos.reserva', $espaco['id']) }}" method="POST" class="mt-6 space-y-5" aria-describedby="reservation-help">
            @csrf
            @foreach(['nome' => ['Nome completo', 'text', 120], 'email' => ['E-mail', 'email', 254], 'data' => ['Data desejada', 'date', null]] as $field => [$label, $type, $max])
                <div>
                    <label class="label" for="reservation-{{ $field }}">{{ $label }} <span class="font-normal">(obrigatório)</span></label>
                    <input id="reservation-{{ $field }}" class="input" name="{{ $field }}" type="{{ $type }}" value="{{ old($field) }}" required @if($max) maxlength="{{ $max }}" @endif @if($field === 'nome') minlength="3" @endif @if($field === 'data') min="{{ \Illuminate\Support\Carbon::now('America/Bahia')->toDateString() }}" @else autocomplete="{{ $field === 'nome' ? 'name' : 'email' }}" @endif aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @if($errors->has($field)) aria-describedby="reservation-{{ $field }}-error" @endif>
                    @error($field)<p id="reservation-{{ $field }}-error" class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                </div>
            @endforeach
            <div>
                <label class="label" for="reservation-purpose">Finalidade <span class="font-normal">(obrigatório)</span></label>
                <textarea id="reservation-purpose" class="input min-h-28" name="finalidade" minlength="10" maxlength="1000" required placeholder="Conte qual atividade deseja realizar…" aria-invalid="{{ $errors->has('finalidade') ? 'true' : 'false' }}" aria-describedby="purpose-help @error('finalidade') purpose-error @enderror">{{ old('finalidade') }}</textarea>
                <p id="purpose-help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Entre 10 e 1000 caracteres.</p>
                @error('finalidade')<p id="purpose-error" class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
            </div>
            <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Enviar solicitação</button>
        </form>
    </aside>
</section>
@endsection
