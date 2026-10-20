@extends('layouts.app')

@section('title', 'Cadastre sua OSC — UOSCC')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 py-14 sm:py-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_12%_18%,rgba(47,158,89,.45),transparent_28%),radial-gradient(circle_at_88%_12%,rgba(108,194,142,.25),transparent_24%),radial-gradient(circle_at_75%_88%,rgba(47,158,89,.2),transparent_30%)]"></div>
        <div class="glow-orb absolute -left-16 top-28 h-72 w-72 rounded-full bg-emerald-300/20 blur-3xl"></div>
        <div class="glow-orb absolute -right-20 bottom-20 h-80 w-80 rounded-full bg-teal-300/15 blur-3xl" style="animation-delay: 3s;"></div>
        <x-particles variant="light" />

        <div class="section-shell relative">
            <div class="mx-auto max-w-3xl">
                <p class="eyebrow text-emerald-300">Cadastro de OSC</p>
                <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">Cadastre sua organização</h1>
                <p class="mt-3 max-w-2xl text-slate-200">A própria OSC informa seus dados e cria a conta da pessoa responsável por publicar eventos e projetos.</p>

                <form action="{{ route('organizacoes.cadastro.store') }}" method="POST" class="mt-8 grid gap-5 rounded-3xl border border-white/20 bg-white/95 p-6 shadow-2xl shadow-black/20 backdrop-blur sm:grid-cols-2 sm:p-8 dark:border-slate-700 dark:bg-slate-900/95">
                @csrf
                <div class="sm:col-span-2">
                    <label class="label" for="organization-name">Nome da organização</label>
                    <input id="organization-name" class="input" name="name" value="{{ old('name') }}" maxlength="255" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="organization-description">Descrição</label>
                    <textarea id="organization-description" class="input min-h-32" name="description" minlength="10" required>{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="label" for="organization-email">E-mail de contato</label>
                    <input id="organization-email" class="input" name="contact_email" type="email" value="{{ old('contact_email') }}" maxlength="255">
                </div>
                <div>
                    <label class="label" for="organization-phone">Telefone</label>
                    <input id="organization-phone" class="input" name="contact_phone" value="{{ old('contact_phone') }}" maxlength="255">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="organization-social-media">Redes sociais</label>
                    <input id="organization-social-media" class="input" name="social_media_links" value="{{ old('social_media_links') }}" maxlength="255" placeholder="Ex.: https://instagram.com/minhaosc">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="organization-focus-area">Área de atuação</label>
                    <select id="organization-focus-area" class="input" name="focus_area" required>
                        <option value="">Selecione uma área</option>
                        @foreach(\App\Enums\FocusArea::cases() as $focusArea)
                            <option value="{{ $focusArea->value }}" @selected(old('focus_area') === $focusArea->value)>{{ $focusArea->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2 border-t border-slate-200 pt-5 dark:border-slate-700">
                    <h2 class="text-lg font-black">Pessoa responsável pela OSC</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Esta conta poderá cadastrar eventos e projetos da organização.</p>
                </div>
                <div>
                    <label class="label" for="manager-name">Nome completo</label>
                    <input id="manager-name" class="input" name="manager_name" value="{{ old('manager_name') }}" maxlength="120" autocomplete="name" required>
                </div>
                <div>
                    <label class="label" for="manager-email">E-mail de acesso</label>
                    <input id="manager-email" class="input" name="manager_email" type="email" value="{{ old('manager_email') }}" maxlength="254" autocomplete="email" required>
                </div>
                <div>
                    <label class="label" for="manager-password">Senha</label>
                    <input id="manager-password" class="input" name="password" type="password" minlength="8" autocomplete="new-password" required>
                </div>
                <div>
                    <label class="label" for="manager-password-confirmation">Confirme a senha</label>
                    <input id="manager-password-confirmation" class="input" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:col-span-2 sm:flex-row sm:justify-end dark:border-slate-700">
                    <a class="rounded-xl px-5 py-3 text-center text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800" href="{{ route('home') }}">Cancelar</a>
                    <button class="rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white hover:bg-uoscc-dark">Salvar organização</button>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection
