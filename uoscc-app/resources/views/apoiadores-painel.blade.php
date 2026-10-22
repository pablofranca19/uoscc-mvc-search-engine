@extends('layouts.app')
@section('title', 'Painel do apoiador — UOSCC')
@section('content')
<section class="section-shell py-10">
    <x-supporter-nav />
    <header class="rounded-3xl bg-slate-950 p-6 text-white sm:p-10"><p class="text-xs font-bold uppercase tracking-wider text-emerald-300">Acesso de apoiador aprovado</p><h1 class="mt-3 text-3xl font-black sm:text-4xl">Olá, {{ auth()->user()->name }}!</h1><p class="mt-4 max-w-2xl leading-7 text-slate-300">Sua solicitação foi aprovada. Este é seu espaço para acompanhar oportunidades e manter seu perfil atualizado.</p></header>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900"><p class="eyebrow">Oportunidades</p><h2 class="mt-3 text-2xl font-black">Encontre formas de contribuir</h2><p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">Não há oportunidades exclusivas cadastradas nesta instalação. Nenhum convite fictício é exibido.</p><a href="{{ route('apoiadores.oportunidades') }}" class="mt-5 inline-block rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white hover:bg-uoscc-dark">Consultar oportunidades →</a></section>
        <section class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900"><p class="eyebrow">Meu perfil</p><h2 class="mt-3 text-2xl font-black">Seu vínculo com a rede</h2><p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $application->applicant_type === 'person' ? 'Participação como pessoa' : $application->organization_name }}</p><p class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $application->interests }}</p><a href="{{ route('apoiadores.perfil') }}" class="mt-5 inline-block font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Consultar e editar meu perfil →</a></section>
    </div>
    <aside class="mt-8 rounded-2xl border border-sky-200 bg-sky-50 p-5 text-sm leading-7 text-sky-950 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-100"><h2 class="font-bold">Informações desta instalação</h2><p>O acesso e sua solicitação são registrados no banco local. O cadastro e a publicação de oportunidades ainda não foram implementados. Os eventos, espaços e organizações do site público continuam com conteúdo demonstrativo.</p></aside>
</section>
@endsection
