@extends('layouts.app')
@section('title', 'Organizações — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20"><p class="eyebrow">Rede UOSCC</p><h1 class="section-title">Organizações</h1><p class="section-copy">Conheça as organizações, suas áreas de atuação e os projetos que aproximam a sociedade civil da comunidade.</p><div class="mt-8"><input class="input max-w-xl" data-filter="#org-list" placeholder="Buscar organização ou área de atuação..."></div><div id="org-list" class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">@foreach($organizacoes as $organizacao)<div data-filter-item><x-org-card :organizacao="$organizacao" /></div>@endforeach</div></section>
@endsection
