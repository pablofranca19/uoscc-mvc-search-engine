@extends('layouts.app')

@section('title', 'Cadastrar evento — UOSCC')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">Área do apoiador</p>
            <h1 class="mt-2 text-3xl font-black sm:text-4xl">Cadastre um evento ou projeto</h1>

            @if ($spaces->isEmpty())
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-200">
                    Sua conta ainda não está vinculada a uma organização com espaços. Você pode publicar sem escolher um espaço ou cadastrar uma organização primeiro.
                </div>
            @endif

            <form action="{{ route('eventos.store') }}" method="POST" class="mt-8 grid gap-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2 sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                @csrf
                <div class="sm:col-span-2">
                    <label class="label" for="event-title">Título</label>
                    <input id="event-title" class="input" name="title" value="{{ old('title') }}" maxlength="255" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="event-description">Descrição</label>
                    <textarea id="event-description" class="input min-h-32" name="description" minlength="10" required>{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="label" for="event-date">Data</label>
                    <input id="event-date" class="input" name="activity_date" type="date" value="{{ old('activity_date') }}" required>
                </div>
                <div>
                    <label class="label" for="event-type">Tipo</label>
                    <select id="event-type" class="input" name="activity_type" required>
                        <option value="">Selecione o tipo</option>
                        <option value="Event" @selected(old('activity_type') === 'Event')>Evento</option>
                        <option value="Project" @selected(old('activity_type') === 'Project')>Projeto</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="event-space">Espaço</label>
                    <select id="event-space" class="input" name="space_id">
                        <option value="">Sem espaço definido</option>
                        @foreach ($spaces as $space)
                            <option value="{{ $space->id }}" @selected((string) old('space_id') === (string) $space->id)>{{ $space->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="event-capacity">Número de vagas</label>
                    <input id="event-capacity" class="input" name="capacity" type="number" min="1" value="{{ old('capacity') }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="event-deadline">Prazo de inscrição</label>
                    <input id="event-deadline" class="input" name="registration_deadline" type="date" value="{{ old('registration_deadline') }}">
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:col-span-2 sm:flex-row sm:justify-end dark:border-slate-700">
                    <a class="rounded-xl px-5 py-3 text-center text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800" href="{{ route('apoiadores') }}">Cancelar</a>
                    <button class="rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white hover:bg-uoscc-dark">Publicar</button>
                </div>
            </form>
        </div>
    </section>
@endsection
