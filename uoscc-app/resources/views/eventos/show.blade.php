@extends('layouts.app')
@section('title', $evento['titulo'].' — UOSCC')
@section('content')
@php
    $isProject = ($evento['natureza'] ?? 'evento') === 'projeto';
    $status = \App\ActivityRegistration::status($evento);
    $remaining = max(0, $evento['vagas'] - $evento['inscritos']);
    $statusLabel = ['aberta' => 'Inscrições abertas', 'encerrada' => 'Inscrições encerradas', 'esgotada' => 'Vagas esgotadas'][$status];
@endphp
<section class="bg-slate-950 py-14 text-white sm:py-20">
    <div class="section-shell">
        <a href="{{ route('eventos') }}" class="text-sm font-bold text-emerald-300">← Voltar para eventos e projetos</a>
        <div class="mt-8 flex flex-wrap gap-3 text-xs font-bold">
            <span class="rounded-full bg-emerald-300 px-3 py-1 text-slate-950">{{ $isProject ? 'Projeto' : 'Evento' }}</span>
            <span class="rounded-full border border-white/20 px-3 py-1">{{ $evento['tipo'] }}</span>
        </div>
        <p class="mt-5 text-sm font-bold text-emerald-300">{{ $evento['organizacao'] }}</p>
        <h1 class="mt-3 max-w-4xl text-3xl font-black sm:text-5xl">{{ $evento['titulo'] }}</h1>
        <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">{{ $evento['descricao'] }}</p>
    </div>
</section>
<section class="section-shell grid items-start gap-8 py-12 lg:grid-cols-[1fr_380px]">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-2xl font-black">Sobre {{ $isProject ? 'o projeto' : 'o evento' }}</h2>
        <p class="mt-4 leading-7 text-slate-600 dark:text-slate-300">Consulte as informações da atividade e o período de inscrição. Você pode explorar esta página sem criar uma conta.</p>
        <dl class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach([
                ($isProject ? 'Início do projeto' : 'Data e hora') => \Carbon\Carbon::parse($evento['data'])->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y').' · '.$evento['hora'],
                'Local' => $evento['local'],
                'Região' => $evento['regiao'],
                'Prazo de inscrição' => \Carbon\Carbon::parse($evento['prazo_inscricao'])->format('d/m/Y').' às 23h59 (Bahia)',
            ] as $label => $value)
                <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</dt>
                    <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-200">
            <strong class="block">Ambiente de demonstração</strong>
            Os dados, prazos e vagas são exemplos. Use dados fictícios nos testes: os formulários ainda não registram inscrições.
        </div>
    </div>
    <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg sm:p-7 dark:border-slate-800 dark:bg-slate-900" aria-label="Inscrição na atividade">
        <p class="text-xs font-black uppercase tracking-wider text-uoscc-primary dark:text-uoscc-light">Participação</p>
        <h2 class="mt-2 text-2xl font-black">{{ $statusLabel }}</h2>
        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $remaining }} de {{ $evento['vagas'] }} vagas disponíveis.</p>
        @if($status === 'aberta')
            <p id="registration-help" class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">Teste o preenchimento abaixo. Nenhuma vaga será reservada nesta demonstração.</p>
            <form action="{{ route('eventos.inscricao', $evento['id']) }}" method="POST" class="mt-6 space-y-5" aria-describedby="registration-help">
                @csrf
                @foreach(['nome' => ['Nome completo', 'text', 120, 'name'], 'email' => ['E-mail', 'email', 254, 'email']] as $field => [$label, $type, $max, $autocomplete])
                    <div>
                        <label class="label" for="registration-{{ $field }}">{{ $label }} <span class="font-normal">(obrigatório)</span></label>
                        <input id="registration-{{ $field }}" class="input" type="{{ $type }}" name="{{ $field }}" value="{{ old($field) }}" maxlength="{{ $max }}" @if($field === 'nome') minlength="3" @endif autocomplete="{{ $autocomplete }}" required aria-invalid="{{ $errors->has($field) ? 'true' : 'false' }}" @if($errors->has($field)) aria-describedby="registration-{{ $field }}-error" @endif>
                        @error($field)<p id="registration-{{ $field }}-error" class="mt-2 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                @endforeach
                <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Simular inscrição</button>
            </form>
        @else
            <p class="mt-5 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $status === 'esgotada' ? 'Todas as vagas foram preenchidas. Este protótipo não oferece lista de espera.' : 'O prazo de inscrição terminou. Você ainda pode consultar as informações da atividade.' }}</p>
            <a href="{{ route('eventos') }}" class="mt-6 block rounded-xl bg-slate-900 px-5 py-3 text-center font-bold text-white hover:bg-uoscc-dark dark:bg-white dark:text-slate-900 dark:hover:bg-emerald-300">Buscar outras atividades</a>
        @endif
    </aside>
</section>
@endsection
