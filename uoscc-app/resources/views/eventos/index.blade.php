@extends('layouts.app')
@section('title', 'Eventos e projetos — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20" data-discovery>
    <p class="eyebrow">Agenda pública</p>
    <h1 class="section-title">Eventos e projetos</h1>
    <p class="section-copy">Descubra atividades das organizações e encontre oportunidades perto de você.</p>
    <p class="mt-3 text-sm text-slate-500">Protótipo com dados demonstrativos. A data corresponde ao dia da atividade ou início do projeto.</p>
    <form class="mt-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" data-discovery-form role="search" aria-label="Buscar eventos e projetos">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="label" for="activity-search">O que você procura?</label>
                <input id="activity-search" name="search" type="search" class="input" placeholder="Nome, organização, local ou assunto…">
            </div>
            @foreach([
                'organization' => ['Organização', 'Todas as organizações', 'organizacao'],
                'type' => ['Tipo de atividade', 'Todos os tipos', 'tipo'],
                'region' => ['Região', 'Todas as regiões', 'regiao'],
            ] as $field => [$label, $placeholder, $key])
                <div>
                    <label class="label" for="activity-{{ $field }}">{{ $label }}</label>
                    <select id="activity-{{ $field }}" name="{{ $field }}" class="input">
                        <option value="">{{ $placeholder }}</option>
                        @foreach(collect($eventos)->pluck($key)->unique()->sort() as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
            <div>
                <label class="label" for="activity-date">Data da atividade ou início</label>
                <input id="activity-date" name="date" type="date" class="input">
            </div>
            <div>
                <label class="label" for="activity-kind">Evento ou projeto</label>
                <select id="activity-kind" name="kind" class="input">
                    <option value="">Eventos e projetos</option>
                    <option value="evento">Eventos</option>
                    <option value="projeto">Projetos</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="reset" class="w-full rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100">Limpar filtros</button>
            </div>
        </div>
    </form>
    <p class="mt-8 text-sm font-semibold text-slate-600" data-result-count role="status" aria-live="polite" aria-atomic="true">{{ count($eventos) }} resultados encontrados</p>
    <noscript><p>Ative o JavaScript para usar os filtros. Todas as atividades estão listadas abaixo.</p></noscript>
    <div class="mt-5 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($eventos as $evento)
            <div data-activity data-organization="{{ $evento['organizacao'] }}" data-type="{{ $evento['tipo'] }}" data-region="{{ $evento['regiao'] }}" data-date="{{ $evento['data'] }}" data-kind="{{ $evento['natureza'] ?? 'evento' }}" data-search="{{ $evento['titulo'].' '.$evento['organizacao'].' '.$evento['descricao'].' '.$evento['local'].' '.$evento['regiao'].' '.$evento['tipo'] }}">
                <x-event-card :evento="$evento" />
            </div>
        @endforeach
    </div>
    <div class="mt-6 hidden rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center" data-empty-results>
        <h2 class="text-xl font-bold text-slate-900">Nenhuma atividade encontrada</h2>
        <p class="mt-3 text-slate-500">Tente outra palavra, ajuste a data ou limpe os filtros para ver todas as oportunidades.</p>
        <button type="button" data-clear-filters class="mt-6 rounded-xl bg-uoscc-primary px-6 py-3 font-bold text-white hover:bg-uoscc-dark">Limpar filtros</button>
    </div>
</section>
@endsection
