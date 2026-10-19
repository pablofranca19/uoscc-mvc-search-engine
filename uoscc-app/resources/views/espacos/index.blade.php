@extends('layouts.app')
@section('title', 'Espaços — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20" data-discovery>
    <p class="eyebrow">Infraestrutura compartilhada</p>
    <h1 class="section-title">Encontre um espaço para sua atividade</h1>
    <p class="section-copy">Compare capacidade, estrutura e região antes de conhecer o espaço e testar uma solicitação.</p>
    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-slate-400">Catálogo demonstrativo. A presença de um espaço na lista não confirma disponibilidade para uma data.</p>
    <form class="mt-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 dark:border-slate-800 dark:bg-slate-900" data-discovery-form role="search" aria-label="Buscar espaços">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="label" for="space-search">O que você procura?</label>
                <input id="space-search" name="search" type="search" class="input" placeholder="Espaço, organização, região ou estrutura…">
            </div>
            <div>
                <label class="label" for="space-capacity">Capacidade mínima</label>
                <input id="space-capacity" name="capacity" type="number" min="1" step="1" class="input" placeholder="Ex.: 40" aria-describedby="space-capacity-help">
                <p id="space-capacity-help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">Quantidade de pessoas que precisam caber no espaço.</p>
            </div>
            <div>
                <label class="label" for="space-region">Região</label>
                <select id="space-region" name="region" class="input">
                    <option value="">Todas as regiões</option>
                    @foreach(collect($espacos)->pluck('regiao')->unique()->sort() as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="space-structure">Estrutura necessária</label>
                <select id="space-structure" name="structure" class="input">
                    <option value="">Qualquer estrutura</option>
                    @foreach(collect($espacos)->pluck('estrutura')->flatten()->unique()->sort() as $structure)
                        <option value="{{ $structure }}">{{ $structure }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="reset" class="mt-5 rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Limpar filtros</button>
    </form>
    <p class="mt-8 text-sm font-semibold text-slate-600 dark:text-slate-300" data-result-count role="status" aria-live="polite" aria-atomic="true">{{ count($espacos) }} resultados encontrados</p>
    <noscript><p class="mt-3 text-sm">Ative o JavaScript para usar os filtros. Os espaços estão disponíveis abaixo.</p></noscript>
    <div class="mt-5 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($espacos as $espaco)
            <div data-space data-capacity="{{ $espaco['capacidade'] }}" data-region="{{ $espaco['regiao'] }}" data-structure="{{ json_encode($espaco['estrutura']) }}" data-search="{{ $espaco['nome'].' '.$espaco['organizacao'].' '.$espaco['descricao'].' '.$espaco['regiao'].' '.implode(' ', $espaco['estrutura']) }}">
                <x-space-card :espaco="$espaco" />
            </div>
        @endforeach
    </div>
    <div class="mt-6 hidden rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900" data-empty-results>
        <h2 class="text-xl font-bold dark:text-white">Nenhum espaço encontrado</h2>
        <p class="mt-3 text-slate-500 dark:text-slate-400">Tente reduzir a capacidade mínima, mudar a estrutura ou escolher outra região.</p>
        <button type="button" data-clear-filters class="mt-6 rounded-xl bg-uoscc-primary px-6 py-3 font-bold text-white hover:bg-uoscc-dark">Limpar filtros</button>
    </div>
</section>
@endsection
