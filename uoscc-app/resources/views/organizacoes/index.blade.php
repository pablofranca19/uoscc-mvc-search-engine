@extends('layouts.app')
@section('title', 'Organizações — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20" data-discovery>
    <p class="eyebrow">Rede UOSCC</p>
    <h1 class="section-title">Conheça quem faz acontecer</h1>
    <p class="section-copy">Explore as organizações por área de atuação ou região e conheça suas atividades e espaços.</p>
    <p class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">Este catálogo reúne perfis fictícios para demonstrar a proposta. Nomes e contatos deverão ser substituídos pelas informações confirmadas das OSCs.</p>

    <form class="mt-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" data-discovery-form role="search" aria-label="Buscar organizações">
        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="label" for="organization-search">Qual organização você procura?</label>
                <input id="organization-search" class="input" name="search" type="search" placeholder="Nome ou assunto…">
            </div>
        </div>
        <button type="reset" class="mt-5 rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100">Limpar filtros</button>
    </form>

    <p class="mt-8 text-sm font-semibold text-slate-600" data-result-count role="status" aria-live="polite" aria-atomic="true">{{ count($organizacoes) }} resultados encontrados</p>
    <noscript><p class="mt-3 text-sm">Ative o JavaScript para usar os filtros. Os perfis continuam disponíveis abaixo.</p></noscript>
    <div class="mt-5 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($organizacoes as $organizacao)
            <div data-organization-card data-search="{{ $organizacao['nome'].' '.$organizacao['descricao'] }}">
                <x-org-card :organizacao="$organizacao" />
            </div>
        @endforeach
    </div>
    <div class="mt-6 hidden rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center" data-empty-results>
        <h2 class="text-xl font-bold">Nenhuma organização encontrada</h2>
        <p class="mt-3 text-slate-500">Ajuste a busca ou a região para explorar outros perfis.</p>
        <button type="button" data-clear-filters class="mt-6 rounded-xl bg-uoscc-primary px-6 py-3 font-bold text-white hover:bg-uoscc-dark">Limpar filtros</button>
    </div>
</section>
@endsection
