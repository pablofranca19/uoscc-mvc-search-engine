@props(['evento'])
@php
    $data = \Carbon\Carbon::parse($evento['data'])->locale('pt_BR');
    $restantes = max(0, $evento['vagas'] - $evento['inscritos']);
    $statusInscricao = \App\ActivityRegistration::status($evento);
@endphp
<article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900">
    <div class="relative min-h-40 overflow-hidden bg-gradient-to-br from-uoscc-primary via-emerald-500 to-uoscc-dark p-6 text-white">
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
        <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary dark:text-uoscc-light">{{ $evento['organizacao'] }}</p>
        <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $evento['titulo'] }}</h3>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $evento['descricao'] }}</p>
        <div class="mt-4 flex flex-col gap-2">
            <span class="text-sm font-bold {{ $statusInscricao === 'aberta' ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-800 dark:text-amber-300' }}">
                {{ ['aberta' => 'Inscrições abertas', 'encerrada' => 'Inscrições encerradas', 'esgotada' => 'Vagas esgotadas'][$statusInscricao] }}
            </span>
            <p class="text-xs text-slate-500 dark:text-slate-400">Prazo: {{ \Carbon\Carbon::parse($evento['prazo_inscricao'])->format('d/m/Y') }}</p>
        </div>
        <div class="mt-auto flex flex-col gap-3 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <span class="text-xs font-semibold {{ $restantes <= 10 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400' }}">{{ $restantes }} vagas restantes</span>
            <a href="{{ route('eventos.show', $evento['id']) }}" class="rounded-xl bg-slate-900 px-4 py-2.5 text-center text-sm font-bold text-white transition hover:bg-uoscc-dark dark:bg-white dark:text-slate-900 dark:hover:bg-emerald-300">Ver detalhes</a>
        </div>
    </div>
</article>
