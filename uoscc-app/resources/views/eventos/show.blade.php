@extends('layouts.app')
@section('title', $evento['titulo'].' — UOSCC')
@section('content')
<section class="bg-slate-950 py-16 text-white"><div class="section-shell"><a href="{{ route('eventos') }}" class="text-sm font-bold text-emerald-300">← Voltar para eventos</a><p class="mt-8 text-sm font-bold uppercase tracking-wider text-emerald-300">{{ $evento['tipo'] }} · {{ $evento['organizacao'] }}</p><h1 class="mt-3 max-w-4xl text-4xl font-black sm:text-5xl">{{ $evento['titulo'] }}</h1><p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">{{ $evento['descricao'] }}</p></div></section>
<section class="section-shell grid gap-8 py-12 lg:grid-cols-[1fr_380px]">
    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
        <h2 class="text-2xl font-black text-slate-900">Sobre o evento</h2><p class="mt-4 leading-8 text-slate-600">Esta atividade é aberta ao público e faz parte da programação da UOSCC. Confira as informações e envie sua inscrição.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-emerald-50 p-5"><p class="text-xs font-bold uppercase text-emerald-700">Data e hora</p><p class="mt-2 font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($evento['data'])->format('d/m/Y') }} · {{ $evento['hora'] }}</p></div>
            <div class="rounded-2xl bg-slate-50 p-5"><p class="text-xs font-bold uppercase text-slate-500">Local</p><p class="mt-2 font-extrabold text-slate-900">{{ $evento['local'] }}</p></div>
        </div>
    </div>
    <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-7 shadow-lg"><p class="text-xs font-black uppercase tracking-wider text-uoscc-primary">Inscrição</p><h2 class="mt-2 text-2xl font-black">Garanta sua vaga</h2><p class="mt-2 text-sm text-slate-500">{{ max(0, $evento['vagas'] - $evento['inscritos']) }} vagas disponíveis.</p><form action="{{ route('eventos.inscricao', $evento['id']) }}" method="POST" class="mt-6 space-y-4">@csrf<label class="label">Nome<input class="input" name="nome" required></label><label class="label">E-mail<input class="input" type="email" name="email" required></label><button class="w-full rounded-xl bg-uoscc-primary px-5 py-3.5 font-black text-white hover:bg-uoscc-dark">Solicitar inscrição</button></form></aside>
</section>
@endsection
