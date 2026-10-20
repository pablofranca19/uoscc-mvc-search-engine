@extends('layouts.app')

@section('title', 'Cadastre sua OSC — UOSCC')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">Cadastro de OSC</p>
            <h1 class="mt-2 text-3xl font-black sm:text-4xl">Cadastre sua organização</h1>
            <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-300">A própria OSC informa seus dados e cria a conta da pessoa responsável por publicar eventos e projetos.</p>

            <form action="{{ route('organizacoes.cadastro.store') }}" method="POST" class="mt-8 grid gap-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
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
    </section>
@endsection
