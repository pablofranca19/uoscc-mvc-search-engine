@extends('layouts.app')

@section('title', 'Painel da OSC — UOSCC')

@section('content')
    <section class="bg-emerald-50 py-14 text-slate-900 dark:bg-slate-950 dark:text-white sm:py-20">
        <div class="section-shell flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow text-emerald-300">Área da organização</p>
                <h1 class="mt-3 text-3xl font-black sm:text-5xl">{{ $organization->name }}</h1>
                <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">Gerencie as atividades e acompanhe as informações públicas da sua OSC.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('espacos.create') }}" class="rounded-xl border border-emerald-300 px-5 py-3 text-center text-sm font-bold text-emerald-900 transition hover:bg-white dark:border-white/30 dark:text-white dark:hover:bg-white/10">Cadastrar espaço</a>
                <a href="{{ route('eventos.create') }}" class="rounded-xl bg-emerald-400 px-5 py-3 text-center text-sm font-bold text-slate-950 shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-300">Publicar evento ou projeto</a>
            </div>
        </div>
    </section>

    <section class="section-shell grid gap-8 py-12 lg:grid-cols-[minmax(0,1.1fr)_340px]">
        <div class="space-y-8">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30 sm:p-8">
                <p class="eyebrow">Perfil da OSC</p>
                <h2 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Informações da organização</h2>
                <p class="mt-4 leading-7 text-slate-600 dark:text-slate-300">{{ $organization->description }}</p>
                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-emerald-50 p-5 dark:bg-emerald-400/10">
                        <dt class="text-xs font-bold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">Área de atuação</dt>
                        <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $organization->focus_area->label() }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Contato público</dt>
                        <dd class="mt-2 font-bold text-slate-900 dark:text-white">{{ $organization->contact_email ?? 'Ainda não informado' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="eyebrow">Publicações</p>
                        <h2 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Eventos e projetos da OSC</h2>
                    </div>
                    <a href="{{ route('eventos.create') }}" class="text-sm font-bold text-uoscc-primary dark:text-emerald-300">Nova publicação →</a>
                </div>
                <div class="mt-6 space-y-4">
                    @forelse($activities as $activity)
                        <article class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-bold uppercase tracking-wide text-uoscc-primary dark:text-emerald-300">{{ $activity->activity_type === \App\Enums\ActivityType::Project ? 'Projeto' : 'Evento' }} · {{ $activity->activity_date->format('d/m/Y') }}</p>
                            <h3 class="mt-2 font-black text-slate-900 dark:text-white">{{ $activity->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $activity->space?->name ?? 'Sem espaço definido' }}</p>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-sm leading-6 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                            Você ainda não publicou eventos ou projetos. Crie a primeira publicação para ela aparecer no catálogo público.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-3xl bg-uoscc-dark p-6 text-white shadow-lg shadow-emerald-950/30">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-200">Resumo</p>
                <dl class="mt-5 grid gap-4">
                    <div class="rounded-2xl bg-white/10 p-4"><dt class="text-sm text-emerald-100">Eventos e projetos</dt><dd class="mt-1 text-3xl font-black">{{ $organization->owned_activities_count }}</dd></div>
                    <div class="rounded-2xl bg-white/10 p-4"><dt class="text-sm text-emerald-100">Espaços cadastrados</dt><dd class="mt-1 text-3xl font-black">{{ $organization->spaces_count }}</dd></div>
                </dl>
            </section>
            <a href="{{ route('organizacoes.show', $organization->id) }}" class="block rounded-2xl border border-emerald-300 bg-white p-5 text-sm font-bold text-emerald-900 transition hover:bg-emerald-50 dark:border-emerald-700 dark:bg-slate-900 dark:text-emerald-200 dark:hover:bg-slate-800">Ver perfil público da OSC →</a>
        </aside>
    </section>
@endsection
