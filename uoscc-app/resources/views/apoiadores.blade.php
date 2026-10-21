@extends('layouts.app')
@section('title', 'Apoiadores — UOSCC')
@section('content')
<section class="section-shell py-12 sm:py-20">
    <p class="eyebrow">Faça parte da rede</p>
    <h1 class="section-title">Apoiar começa com uma conexão</h1>
    <p class="section-copy">Pessoas e organizações podem se aproximar da rede e apresentar como gostariam de contribuir com a comunidade.</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('apoiadores.login') }}" class="rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white hover:bg-uoscc-dark">Como participar</a>
        <a href="{{ route('organizacoes') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold dark:border-slate-700">Conhecer as organizações</a>
    </div>
    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <section class="rounded-3xl border border-emerald-200 bg-white p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
            <p class="eyebrow">Disponível sem cadastro</p><h2 class="mt-3 text-2xl font-black">Consulta pública</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">Conheça eventos, projetos, organizações e espaços sem criar uma conta. Os catálogos públicos ainda contêm exemplos e os formulários públicos de inscrição e reserva continuam demonstrativos.</p>
            <a href="{{ route('eventos') }}" class="mt-5 inline-block font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Explorar eventos e projetos →</a>
        </section>
        <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
            <p class="eyebrow">Área restrita</p><h2 class="mt-3 text-2xl font-black">Acesso após aprovação</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">Crie sua conta, envie uma solicitação e acompanhe a análise. Após a aprovação, você terá acesso ao painel, à página de oportunidades e ao seu perfil. A aprovação de apoiador não concede acesso administrativo.</p>
        </section>
    </div>
    <section class="mt-12" aria-labelledby="steps-title">
        <h2 id="steps-title" class="text-2xl font-black">Como funciona</h2>
        <ol class="mt-6 grid gap-5 md:grid-cols-3">
            @foreach(['Crie sua conta' => 'Cadastre nome, e-mail e senha para acessar sua solicitação.', 'Apresente seu interesse' => 'Conte suas áreas de interesse e como pretende contribuir.', 'Acompanhe a análise' => 'A equipe aprova o acesso ou pede ajustes. O resultado fica disponível na sua conta.'] as $title => $description)
                <li class="rounded-2xl border border-slate-200 p-6 dark:border-slate-800"><span class="font-black text-uoscc-dark dark:text-uoscc-light">0{{ $loop->iteration }}</span><h3 class="mt-3 text-lg font-bold">{{ $title }}</h3><p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $description }}</p></li>
            @endforeach
        </ol>
    </section>
</section>
@endsection
