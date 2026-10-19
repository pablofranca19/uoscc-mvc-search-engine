@props(['espaco'])
<article class="flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
    <div class="min-h-32 bg-gradient-to-br from-slate-800 to-uoscc-dark p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-200">{{ $espaco['organizacao'] }}</p>
        <h3 class="mt-2 text-xl font-extrabold">{{ $espaco['nome'] }}</h3>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Até {{ $espaco['capacidade'] }} pessoas</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $espaco['regiao'] }}</span>
        </div>
        <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $espaco['descricao'] }}</p>
        <h4 class="mt-5 text-sm font-bold text-slate-800 dark:text-slate-100">Estrutura</h4>
        <ul class="mt-2 flex flex-wrap gap-2">
            @foreach($espaco['estrutura'] as $item)
                <li class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">{{ $item }}</li>
            @endforeach
        </ul>
        <p class="mb-5 mt-4 text-xs leading-5 text-slate-500 dark:text-slate-400">A disponibilidade da data depende de análise da OSC.</p>
        <a href="{{ route('espacos.show', $espaco['id']) }}" class="mt-auto block rounded-xl bg-uoscc-primary px-4 py-3 text-center text-sm font-bold text-white hover:bg-uoscc-dark">Ver espaço e solicitar reserva</a>
    </div>
</article>
