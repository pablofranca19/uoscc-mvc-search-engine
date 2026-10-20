@extends('layouts.app')
@section('content')
<section class="section-shell py-14"><div class="flex justify-between"><h1 class="section-title">Eventos cadastrados</h1><a class="rounded-xl bg-uoscc-primary px-5 py-3 font-bold text-white" href="{{ route('gestao-eventos.create') }}">Cadastrar evento</a></div><ul class="mt-8 space-y-3">@foreach($eventos as $evento)<li class="rounded-xl bg-white p-4 text-slate-900 shadow-sm dark:bg-slate-900 dark:text-white">{{ $evento->titulo }} — {{ $evento->regiao }}</li>@endforeach</ul></section>
@endsection
