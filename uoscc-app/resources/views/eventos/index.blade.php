@extends('layouts.app')
@section('title', 'Eventos — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20">
    <p class="eyebrow">Agenda pública</p><h1 class="section-title">Eventos</h1><p class="section-copy">Encontre ações, oficinas, encontros e atividades abertas à comunidade.</p>
    <div class="mt-10 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2"><label class="label" for="event-search">Pesquisar</label><input id="event-search" class="input" data-filter="#event-list" placeholder="Digite o nome, organização ou atividade..."></div>
            <div><label class="label" for="event-region">Região</label><select id="event-region" class="input" data-region-filter="#event-list"><option>Todas as regiões</option><option>Centro</option><option>Orla</option><option>Parque Verde</option><option>Gleba A</option></select></div>
        </div>
    </div>
    <div id="event-list" class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($eventos as $evento)
            <div data-filter-item data-region="{{ $evento['regiao'] }}"><x-event-card :evento="$evento" /></div>
        @endforeach
    </div>
</section>
@endsection
