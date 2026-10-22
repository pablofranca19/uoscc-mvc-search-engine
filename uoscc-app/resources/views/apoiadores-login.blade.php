@extends('layouts.app')
@section('title', 'Entrar — UOSCC')
@section('content')
<section class="section-shell grid items-start gap-8 py-12 sm:py-16 lg:grid-cols-2">
    <div>
        <a href="{{ route('apoiadores') }}" class="text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">← Sobre a área de apoiadores</a>
        <p class="eyebrow mt-8">Conexões que fortalecem</p>
        <h1 class="section-title">Vamos caminhar junto à rede</h1>
        <p class="section-copy">Entre para acompanhar sua solicitação. Após a aprovação da equipe, sua conta terá acesso ao painel de apoiadores.</p>
        <div class="mt-8 rounded-2xl border border-emerald-200 p-5 text-sm leading-7 dark:border-emerald-900">Primeira visita? Crie sua conta e conte como gostaria de contribuir. Criar a conta não libera automaticamente a área exclusiva.</div>
        <a href="{{ route('eventos') }}" class="mt-6 inline-block text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Explorar o site público sem entrar →</a>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
        <nav aria-label="Acesso à conta" class="mb-6 flex flex-wrap gap-4 text-sm font-bold"><a href="{{ route('apoiadores.login') }}" aria-current="page" class="rounded-xl bg-emerald-50 px-4 py-3 text-uoscc-dark dark:bg-slate-800 dark:text-uoscc-light">Entrar</a><a href="{{ route('apoiadores.cadastro') }}" class="px-4 py-3 underline underline-offset-4">Criar conta</a></nav>
        <h2 class="text-2xl font-black">Entre na sua conta</h2>
        <form action="{{ route('apoiadores.login.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            <x-supporter-field name="email" label="E-mail" type="email" autocomplete="username" maxlength="254" />
            <x-supporter-field name="password" label="Senha" type="password" autocomplete="current-password" maxlength="72" />
            <button data-password-toggle="password" type="button" hidden aria-controls="password" aria-pressed="false" class="text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Mostrar senha</button>
            <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Entrar</button>
        </form>
<details class="mt-6 border-t border-slate-200 pt-5 dark:border-slate-800"><summary class="cursor-pointer text-sm font-bold">Esqueceu sua senha?</summary><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">A recuperação por e-mail ainda não foi configurada neste ambiente local. Não há envio de mensagens nesta versão.</p></details>
    </div>
</section>
@endsection
