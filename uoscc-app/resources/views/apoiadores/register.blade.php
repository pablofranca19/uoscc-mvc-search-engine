@extends('layouts.app')

@section('title', 'Cadastro de apoiador')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <div class="mx-auto max-w-xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
            <p class="eyebrow">Área do apoiador</p>
            <h1 class="mt-2 text-3xl font-black">Crie sua conta</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Use seu e-mail e uma senha para acessar seu painel pessoal de apoiador.</p>

            <form action="{{ route('apoiadores.cadastro.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="label" for="register-name">Nome completo</label>
                    <input id="register-name" class="input" name="name" value="{{ old('name') }}" maxlength="120" autocomplete="name" required>
                </div>
                <div>
                    <label class="label" for="register-email">E-mail</label>
                    <input id="register-email" class="input" type="email" name="email" value="{{ old('email') }}" maxlength="254" autocomplete="email" required>
                </div>
                <div>
                    <label class="label" for="register-password">Senha</label>
                    <div class="relative">
                        <input id="register-password" class="input pr-12" type="password" name="password" minlength="8" autocomplete="new-password" required>
                        <button type="button" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-500 hover:text-uoscc-primary dark:text-slate-400" data-password-toggle="register-password register-password-confirmation" aria-controls="register-password register-password-confirmation" aria-label="Mostrar senhas" title="Mostrar senhas">
                            <svg data-password-icon="hidden" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg data-password-icon="visible" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A11.5 11.5 0 0 1 12 5c6.5 0 10 7 10 7a18.4 18.4 0 0 1-3.2 4.1M6.2 6.2C3.6 8.1 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4-.8"/></svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Use pelo menos 8 caracteres.</p>
                </div>
                <div>
                    <label class="label" for="register-password-confirmation">Confirme a senha</label>
                    <input id="register-password-confirmation" class="input" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required>
                </div>
                <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Criar conta</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600 dark:text-slate-300">Já possui uma conta? <a href="{{ route('login') }}" class="font-bold text-uoscc-primary underline underline-offset-4">Entrar</a></p>
        </div>
    </section>
@endsection
