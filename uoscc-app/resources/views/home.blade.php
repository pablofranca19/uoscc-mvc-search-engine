@extends('layouts.app')
@section('title', 'UOSCC — Início')
@section('content')
<section class="relative overflow-hidden bg-slate-950">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(47,158,89,.35),transparent_35%),radial-gradient(circle_at_90%_80%,rgba(108,194,142,.18),transparent_30%)]"></div>
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
                <div class="rounded-3xl bg-gradient-to-br from-uoscc-primary to-uoscc-dark p-8 text-white">
                    <p class="text-sm font-bold text-emerald-100">Explore a rede neste protótipo</p>
                    <p class="mt-2 text-xs leading-5 text-emerald-100">Dados demonstrativos, calculados a partir do conteúdo disponível.</p>
                    <div class="mt-10 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black">{{ count($organizacoes) }}</strong><p class="mt-1 text-xs text-emerald-100">organizações no catálogo</p></div>
                        <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black">{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'projeto')) }}</strong><p class="mt-1 text-xs text-emerald-100">projetos no catálogo</p></div>
                        <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black">{{ count(array_filter($eventos, static fn (array $atividade): bool => ($atividade['natureza'] ?? 'evento') === 'evento')) }}</strong><p class="mt-1 text-xs text-emerald-100">eventos no catálogo</p></div>
                        <div class="rounded-2xl bg-white/10 p-5"><strong class="text-3xl font-black">{{ count($espacos) }}</strong><p class="mt-1 text-xs text-emerald-100">espaços no catálogo</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell py-20">
    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div><p class="eyebrow">Conheça as iniciativas</p><h2 class="section-title">Eventos e projetos em destaque</h2><p class="section-copy">Explore ações, oficinas e encontros promovidos pelas organizações.</p></div>
        <a href="{{ route('eventos') }}" class="font-bold text-uoscc-primary hover:text-uoscc-dark">Ver eventos e projetos →</a>
    </div>
    <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach(array_slice($eventos, 0, 3) as $evento)
            <x-event-card :evento="$evento" />
        @endforeach
    </div>
</section>

<section class="bg-white py-20">
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
    <div class="rounded-[2rem] bg-uoscc-primary p-8 text-white sm:p-12">
        <div class="max-w-3xl"><p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-100">Faça parte</p><h2 class="mt-3 text-3xl font-black sm:text-4xl">Quer participar de uma iniciativa?</h2><p class="mt-4 leading-7 text-emerald-50">Consulte os eventos disponíveis, veja as vagas e encontre a organização mais próxima dos seus interesses.</p><a href="{{ route('eventos') }}" class="mt-7 inline-flex rounded-xl bg-white px-6 py-3 font-black text-uoscc-dark hover:bg-emerald-50">Explorar oportunidades</a></div>
    </div>
</section>
@endsection
