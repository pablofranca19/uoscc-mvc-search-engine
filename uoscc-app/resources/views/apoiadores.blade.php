@extends('layouts.app')
@section('title', 'Acesso de apoiadores — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20">
    <p class="eyebrow">Entenda os acessos</p>
    <h1 class="section-title">Uma rede aberta para conhecer</h1>
    <p class="section-copy">A consulta pública é livre. O futuro acesso de apoiadores será uma área separada, com cadastro e permissões definidos pela equipe.</p>
    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <section class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm sm:p-8" aria-labelledby="public-access-title">
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800">Disponível · sem cadastro</span>
            <h2 id="public-access-title" class="mt-5 text-2xl font-black">Consulta pública</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Qualquer pessoa pode conhecer as iniciativas da rede e consultar informações publicadas pelas organizações.</p>
            <ul class="mt-6 space-y-4 text-sm font-semibold text-uoscc-dark">
                <li><a href="{{ route('eventos') }}" class="underline underline-offset-4">Eventos, projetos, prazos e vagas →</a></li>
                <li><a href="{{ route('espacos') }}" class="underline underline-offset-4">Espaços, capacidade e estrutura →</a></li>
                <li><a href="{{ route('organizacoes') }}" class="underline underline-offset-4">Perfis e contatos públicos das OSCs →</a></li>
            </ul>
            <p class="mt-6 text-sm leading-6 text-slate-500">Os formulários de inscrição e de reserva continuam em demonstração e não registram pedidos.</p>
        </section>
        <section class="rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-8" aria-labelledby="supporter-access-title">
            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-700">Área restrita · em preparação</span>
            <h2 id="supporter-access-title" class="mt-5 text-2xl font-black">Apoiadores cadastrados</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">O grupo ainda definirá quais conteúdos serão exclusivos para apoiadores e quem poderá acessá-los.</p>
            <p class="mt-4 text-sm leading-7 text-slate-600">Não há login, cadastro ou conteúdo privado disponível nesta versão. O acesso dependerá de autenticação e permissões quando essa etapa for implementada.</p>
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="font-bold">O que você pode fazer agora?</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">Explore as informações públicas e conheça as organizações. Nenhuma conta é necessária para consultar.</p>
            </div>
        </section>
    </div>
</section>
@endsection