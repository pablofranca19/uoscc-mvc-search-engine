@extends('layouts.app')
@section('title', 'Espaços — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20"><p class="eyebrow">Infraestrutura compartilhada</p><h1 class="section-title">Espaços para atividades</h1><p class="section-copy">Consulte espaços disponibilizados pelas organizações e envie uma solicitação de reserva.</p><div class="mt-8"><input class="input max-w-xl" data-filter="#space-list" placeholder="Buscar espaço, organização ou região..."></div><div id="space-list" class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">@foreach($espacos as $espaco)<div data-filter-item><x-space-card :espaco="$espaco" /></div>@endforeach</div></section>
@endsection
