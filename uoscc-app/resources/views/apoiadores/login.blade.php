@extends('layouts.app')

@section('title', 'Entrar como apoiador — UOSCC')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <div class="mx-auto max-w-xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
            <p class="eyebrow">Área do apoiador</p>
            <h1 class="mt-2 text-3xl font-black">Entre na sua conta</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Acesse seus favoritos, inscrições e perfil de voluntariado.</p>

            <form action="{{ route('apoiadores.login') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="label" for="login-email">E-mail</label>
                    <input id="login-email" class="input" type="email" name="email" value="{{ old('email') }}" maxlength="254" autocomplete="email" required>
                </div>
                <div>
                    <label class="label" for="login-password">Senha</label>
                    <div class="relative">
                        <input id="login-password" class="input pr-12" type="password" name="password" autocomplete="current-password" required>
                        <button type="button" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-500 hover:text-uoscc-primary dark:text-slate-400" data-password-toggle="login-password" aria-controls="login-password" aria-label="Mostrar senha" title="Mostrar senha">
                            <svg data-password-icon="hidden" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg data-password-icon="visible" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A11.5 11.5 0 0 1 12 5c6.5 0 10 7 10 7a18.4 18.4 0 0 1-3.2 4.1M6.2 6.2C3.6 8.1 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4-.8"/></svg>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300"><input type="checkbox" name="remember" value="1"> Manter minha sessão neste dispositivo</label>
                <button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-bold text-white hover:bg-uoscc-dark">Entrar</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600 dark:text-slate-300">Ainda não possui uma conta? <a href="{{ route('apoiadores.cadastro') }}" class="font-bold text-uoscc-primary underline underline-offset-4">Criar conta</a></p>
        </div>
    </section>
@endsection
