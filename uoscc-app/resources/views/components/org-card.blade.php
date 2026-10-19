@props(['organizacao'])
<article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-start gap-4">
        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-emerald-50 font-black text-uoscc-primary dark:bg-emerald-950/50 dark:text-uoscc-light">{{ $organizacao['sigla'] }}</div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary dark:text-uoscc-light">{{ $organizacao['area'] }}</p>
            <h3 class="mt-1 font-extrabold text-slate-900 dark:text-white">{{ $organizacao['nome'] }}</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $organizacao['regiao'] }}</p>
        </div>
    </div>
    <p class="mt-5 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $organizacao['descricao'] }}</p>
    <p class="mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Perfil demonstrativo</p>
    <a href="{{ route('organizacoes.show', $organizacao['id']) }}" class="mt-auto inline-flex pt-5 text-sm font-bold text-uoscc-primary hover:text-uoscc-dark dark:text-uoscc-light dark:hover:text-white" aria-label="Conhecer {{ $organizacao['nome'] }}">Conhecer organização →</a>
</article>
