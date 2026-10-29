//@extends('layouts.app')

@section('title', 'Minhas reservas de espaços — UOSCC')

@section('content')
    <section class="bg-emerald-50 py-14 text-slate-900 dark:bg-slate-950 dark:text-white sm:py-20">
        <div class="section-shell">
            <a href="{{ route('apoiadores') }}" class="text-sm font-bold text-uoscc-primary dark:text-emerald-300">← Voltar ao painel</a>
            <p class="mt-8 eyebrow">Área do apoiador</p>
            <h1 class="mt-3 text-3xl font-black sm:text-5xl">Minhas reservas de espaços</h1>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">Acompanhe se a organização responsável aprovou ou recusou cada solicitação.</p>
        </div>
    </section>

    <section class="section-shell grid gap-8 py-12 lg:grid-cols-[minmax(0,1.1fr)_320px]">
        <div class="space-y-4">
            @forelse($reservas as $reserva)
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary dark:text-emerald-300">{{ $reserva->requested_date->format('d/m/Y') }}</p>
                            <h2 class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ $reserva->space->name }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $reserva->space->organization->name }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold
                            @class([
                                'bg-amber-50 text-amber-900 dark:bg-amber-400/10 dark:text-amber-200' => $reserva->status === \App\Enums\ReservationStatus::Pending,
                                'bg-emerald-50 text-emerald-900 dark:bg-emerald-400/10 dark:text-emerald-200' => $reserva->status === \App\Enums\ReservationStatus::Approved,
                                'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' => $reserva->status === \App\Enums\ReservationStatus::Rejected,
                            ])">{{ $reserva->status->label() }}</span>
                    </div>
                    <p class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $reserva->purpose }}</p>
                    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Solicitada em {{ $reserva->created_at->timezone('America/Bahia')->format('d/m/Y H:i') }}</p>
                    <a href="{{ route('espacos.show', $reserva->space_id) }}" class="mt-3 inline-block text-sm font-bold text-uoscc-primary underline underline-offset-4 dark:text-emerald-300">Ver página do espaço</a>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-sm leading-6 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                    {{ $mensagem }} <a href="{{ route('espacos') }}" class="font-bold text-uoscc-primary underline underline-offset-4 dark:text-emerald-300">Escolha um espaço</a> para enviar sua primeira solicitação.
                </div>
            @endforelse
        </div>

        <aside class="space-y-6">
            <section class="rounded-3xl bg-uoscc-dark p-6 text-white shadow-lg shadow-emerald-950/30">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-200">Resumo</p>
                <dl class="mt-5 grid gap-4">
                    @foreach(\App\Enums\ReservationStatus::cases() as $status)
                        <div class="rounded-2xl bg-white/10 p-4">
                            <dt class="text-sm text-emerald-100">{{ $status->label() }}</dt>
                            <dd class="mt-1 text-3xl font-black">{{ $reservas->where('status', $status)->count() }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>
        </aside>
    </section>
@endsection
