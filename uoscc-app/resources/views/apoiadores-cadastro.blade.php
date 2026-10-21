@extends('layouts.app')
@section('title', 'Criar conta — UOSCC')
@section('content')
<section class="section-shell max-w-2xl py-12 sm:py-16">
    <p class="eyebrow">Primeiro passo</p>
    <h1 class="section-title">Crie sua conta</h1>
    <p class="section-copy">Depois do cadastro, você preencherá sua solicitação de apoiador. A equipe analisará as informações antes de liberar o portal.</p>
    <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
        <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Nesta instalação, os dados serão guardados no banco local deste computador. Não há confirmação nem recuperação de conta por e-mail configurada.</p>
        <form action="{{ route('apoiadores.cadastro.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            <x-supporter-field name="name" label="Nome completo" autocomplete="name" minlength="3" maxlength="120" />
            <x-supporter-field name="email" label="E-mail" type="email" autocomplete="username" maxlength="254" />
            <x-supporter-field name="password" label="Senha (12 a 72 caracteres)" type="password" autocomplete="new-password" minlength="12" maxlength="72" />
            <button data-password-toggle="password" type="button" hidden aria-controls="password" aria-pressed="false" class="text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Mostrar senha</button>
            <x-supporter-field name="password_confirmation" label="Confirme sua senha" type="password" autocomplete="new-password" minlength="12" maxlength="72" />
            <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Criar conta e continuar</button>
        </form>
        <a href="{{ route('apoiadores.login') }}" class="mt-6 inline-block text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Já tenho conta · Entrar</a>
    </div>
</section>
@endsection
