@extends('layouts.app')
@section('title', 'Oportunidades para apoiadores — UOSCC')
@section('content')
<section class="section-shell py-10"><x-supporter-nav /><p class="eyebrow">Área de apoiadores</p><h1 class="section-title">Oportunidades</h1><div class="mt-8 rounded-3xl border border-dashed border-slate-300 p-8 dark:border-slate-700"><h2 class="text-xl font-black">Nenhuma oportunidade publicada</h2><p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-300">Não há oportunidades reais cadastradas nesta instalação. A publicação e a manifestação de interesse serão implementadas em uma próxima etapa. Seu cadastro de apoiador não envia inscrições automaticamente.</p><a href="{{ route('eventos') }}" class="mt-5 inline-block font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Consultar catálogo público →</a></div></section>
@endsection
