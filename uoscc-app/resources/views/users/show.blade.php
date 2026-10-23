@extends('layouts.app')

@section('title', $usuario->name.' — UOSCC')

@section('content')
    <section class="bg-emerald-50 py-14 text-slate-900 dark:bg-slate-950 dark:text-white sm:py-20">
        <div class="section-shell">
            <p class="eyebrow">Conta de acesso</p>
            <h1 class="mt-3 text-3xl font-black sm:text-5xl">{{ $usuario->name }}</h1>
            <p class="mt-4 text-lg leading-8 text-slate-600 dark:text-slate-300">{{ $usuario->role->label() }}</p>
        </div>
    </section>

    <section class="section-shell py-12">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-8">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">E-mail</dt>
                    <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $usuario->email }}</dd>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Tipo de acesso</dt>
                    <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $usuario->role->label() }}</dd>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-5 dark:bg-emerald-400/10">
                    <dt class="text-xs font-bold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">Organização</dt>
                    <dd class="mt-2 font-bold text-slate-900 dark:text-white">
                        @if($usuario->organization)
                            <a class="underline underline-offset-4" href="{{ route('organizacoes.show', $usuario->organization->id) }}">{{ $usuario->organization->name }}</a>
                        @else
                            Sem vínculo com OSC
                        @endif
                    </dd>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Conta criada em</dt>
                    <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $usuario->created_at->format('d/m/Y') }}</dd>
                </div>
            </dl>
            <div class="mt-8 flex flex-wrap gap-3">
                @if($usuario->isOrganizationManager())
                    <a href="{{ route('organizacoes.perfil') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">Painel da OSC</a>
                @else
                    <a href="{{ route('apoiadores') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">Painel do apoiador</a>
                @endif
            </div>
        </div>
    </section>
@endsection
