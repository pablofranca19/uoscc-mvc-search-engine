@extends('layouts.app')
@section('title', 'UOSCC — Início')
@section('content')
<section class="relative overflow-hidden bg-slate-950">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(47,158,89,.35),transparent_35%),radial-gradient(circle_at_90%_80%,rgba(108,194,142,.18),transparent_30%)]"></div>
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        @foreach([
            [5, 12, 5, 7, 0], [12, 68, 4, 9, 1.5], [19, 38, 6, 11, 0.8],
            [27, 82, 3, 6, 2.2], [34, 25, 5, 8, 3.1], [41, 58, 7, 10, 0.4],
            [49, 14, 4, 7, 4.0], [56, 74, 6, 9, 1.1], [63, 42, 3, 6, 2.7],
            [70, 88, 5, 11, 0.6], [77, 30, 4, 8, 3.4], [84, 62, 6, 10, 1.9],
            [90, 20, 5, 7, 2.9], [94, 50, 3, 9, 4.5], [15, 90, 4, 10, 5.0],
            [38, 70, 8, 12, 1.4], [61, 8, 4, 8, 3.8], [88, 80, 5, 9, 2.1],
        ] as [$left, $bottom, $size, $duration, $delay])
            <span class="particle" style="left: {{ $left }}%; bottom: {{ $bottom }}%; width: {{ $size }}px; height: {{ $size }}px; animation-duration: {{ $duration }}s; animation-delay: {{ $delay }}s;"></span>
        @endforeach
    </div>
    <div class="section-shell relative grid min-h-[560px] items-center gap-12 py-20 lg:grid-cols-[1.1fr_.9fr]">
        <div>
            <span class="inline-flex rounded-full border border-emerald-400/30 bg-emerald-400/10 px-4 py-2 text-xs font-bold uppercase tracking-wider text-emerald-300">Conexão · participação · impacto</span>
            <h1 class="mt-6 max-w-3xl text-4xl font-black leading-tight tracking-tight text-white sm:text-6xl">Organizações que transformam. <span class="text-emerald-400">Pessoas que participam.</span></h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">Encontre eventos, projetos, organizações e espaços da sociedade civil em um só lugar.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('eventos') }}" class="rounded-xl bg-uoscc-primary px-6 py-3.5 text-center text-sm font-black text-white shadow-xl shadow-emerald-950/30 hover:bg-uoscc-light">Encontrar eventos</a>
                <a href="{{ route('organizacoes') }}" class="rounded-xl border border-white/15 bg-white/5 px-6 py-3.5 text-center text-sm font-bold text-white hover:bg-white/10">Conhecer organizações</a>
            </div>
        </div>
        <div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-5 shadow-2xl backdrop-blur">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-uoscc-primary to-uoscc-dark p-8 text-white">
                    <div class="glow-orb absolute -right-8 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
                    <div class="glow-orb absolute -bottom-14 -left-6 h-28 w-28 rounded-full bg-white/10" style="animation-delay: 3.5s;"></div>
                    <x-particles variant="light" />
                    <div class="relative">
                        <p class="text-sm font-bold text-emerald-100">Explore a rede neste protótipo</p>
                        <p class="mt-2 text-xs leading-5 text-emerald-100">Dados demonstrativos, calculados a partir do conteúdo disponível.</p>
                        <div class="mt-10 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black tabular-nums" data-counter="{{ count($organizacoes) }}">{{ count($organizacoes) }}</strong><p class="mt-1 text-xs text-emerald-100">organizações no catálogo</p></div>
                            <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black tabular-nums" data-counter="{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'projeto')) }}">{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'projeto')) }}</strong><p class="mt-1 text-xs text-emerald-100">projetos no catálogo</p></div>
                            <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black tabular-nums" data-counter="{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'evento')) }}">{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'evento')) }}</strong><p class="mt-1 text-xs text-emerald-100">eventos no catálogo</p></div>
                            <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black tabular-nums" data-counter="{{ count($espacos) }}">{{ count($espacos) }}</strong><p class="mt-1 text-xs text-emerald-100">espaços no catálogo</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell py-20">
    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div><p class="eyebrow">Conheça as iniciativas</p><h2 class="section-title">Eventos e projetos em destaque</h2><p class="section-copy">Explore ações, oficinas e encontros promovidos pelas organizações.</p></div>
        <a href="{{ route('eventos') }}" class="font-bold text-uoscc-primary hover:text-uoscc-dark dark:text-uoscc-light dark:hover:text-white">Ver eventos e projetos →</a>
    </div>
    <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach(array_slice($eventos, 0, 3) as $evento)
            <x-event-card :evento="$evento" />
        @endforeach
    </div>
</section>

<section class="bg-white py-20 dark:bg-slate-900">
    <div class="section-shell">
        <div><p class="eyebrow">Quem faz acontecer</p><h2 class="section-title">Organizações da UOSCC</h2><p class="section-copy">Conheça quem está construindo soluções e fortalecendo a comunidade.</p></div>
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($organizacoes as $organizacao)
                <x-org-card :organizacao="$organizacao" />
            @endforeach
        </div>
    </div>
</section>

<section class="section-shell py-20">
    <div class="relative overflow-hidden rounded-[2rem] bg-uoscc-primary p-8 text-white sm:p-12">
        <div class="glow-orb absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="glow-orb absolute -bottom-20 -left-10 h-40 w-40 rounded-full bg-white/10" style="animation-delay: 4s;"></div>
        <x-particles variant="light" />
        <div class="relative max-w-3xl"><p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-100">Faça parte</p><h2 class="mt-3 text-3xl font-black sm:text-4xl">Quer participar de uma iniciativa?</h2><p class="mt-4 leading-7 text-emerald-50">Consulte os eventos disponíveis, veja as vagas e encontre a organização mais próxima dos seus interesses.</p><a href="{{ route('eventos') }}" class="mt-7 inline-flex rounded-xl bg-white px-6 py-3 font-black text-uoscc-dark hover:bg-emerald-50">Explorar oportunidades</a></div>
    </div>
</section>
@endsection
