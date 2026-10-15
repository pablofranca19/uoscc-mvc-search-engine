@props(['evento'])
@php
    $data = \Carbon\Carbon::parse($evento['data'])->locale('pt_BR');
    $restantes = max(0, $evento['vagas'] - $evento['inscritos']);
@endphp
<article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    <div class="relative h-40 overflow-hidden bg-gradient-to-br from-uoscc-primary via-emerald-500 to-uoscc-dark p-6 text-white">
        <div class="absolute -right-8 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap gap-2">
            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-uoscc-dark">{{ ($evento['natureza'] ?? 'evento') === 'projeto' ? 'Projeto' : 'Evento' }}</span>
            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold backdrop-blur">{{ $evento['tipo'] }}</span>
        </div>
        <div class="relative mt-7">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">{{ $data->translatedFormat('d \d\e F') }}</p>
            <p class="mt-1 text-sm font-medium">{{ $evento['hora'] }} · {{ $evento['local'] }}</p>
        </div>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary">{{ $evento['organizacao'] }}</p>
        <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900">{{ $evento['titulo'] }}</h3>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">{{ $evento['descricao'] }}</p>
        <div class="mt-auto flex items-center justify-between gap-4 pt-6">
            <span class="text-xs font-semibold {{ $restantes <= 10 ? 'text-amber-600' : 'text-slate-500' }}">{{ $restantes }} vagas restantes</span>
            <a href="{{ route('eventos.show', $evento['id']) }}" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-uoscc-dark">Ver detalhes</a>
        </div>
    </div>
</article>
