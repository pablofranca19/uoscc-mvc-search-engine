@extends('layouts.app')

@section('title', 'Certificado de participação')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <article class="mx-auto max-w-3xl rounded-3xl border-8 border-emerald-100 bg-white p-8 text-center shadow-sm sm:p-14 dark:border-emerald-900/50 dark:bg-slate-900">
            <p class="text-sm font-bold uppercase tracking-[0.22em] text-uoscc-primary">UOSCC</p>
            <h1 class="mt-5 text-3xl font-black sm:text-4xl">Certificado de participação</h1>
            <p class="mx-auto mt-8 max-w-xl text-lg leading-8 text-slate-600 dark:text-slate-300">Certificamos que <strong>{{ $profile['name'] }}</strong> participou de <strong>{{ $registration['title'] }}</strong>, realizado por {{ $registration['organization'] }}.</p>
            <p class="mt-8 text-sm text-slate-500 dark:text-slate-400">Participação confirmada em {{ \Carbon\Carbon::parse($registration['completed_at'])->format('d/m/Y') }}.</p>
            <div class="mt-10 border-t border-slate-200 pt-6 text-sm font-bold text-uoscc-dark dark:border-slate-700 dark:text-uoscc-light">Rede UOSCC · Sociedade Civil</div>
        </article>
        <div class="mt-8 flex justify-center gap-4 print:hidden">
            <a href="{{ route('apoiadores') }}" class="rounded-xl border border-slate-300 px-5 py-3 font-bold hover:bg-slate-50 dark:border-slate-600 dark:hover:bg-slate-800">Voltar ao painel</a>
            <button type="button" onclick="window.print()" class="rounded-xl bg-uoscc-primary px-5 py-3 font-bold text-white hover:bg-uoscc-dark">Imprimir certificado</button>
        </div>
    </section>
@endsection
