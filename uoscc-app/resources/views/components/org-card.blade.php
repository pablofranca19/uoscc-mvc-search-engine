@props(['organizacao'])
<article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30 dark:hover:border-emerald-700 dark:hover:shadow-emerald-950/30">
    <div class="flex items-start gap-4">
        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-emerald-50 font-black text-uoscc-primary dark:bg-emerald-400/15 dark:text-emerald-300" aria-hidden="true">{{ \Illuminate\Support\Str::substr($organizacao['nome'], 0, 1) }}</div>
        <div>
            <h3 class="mt-1 font-extrabold text-slate-900 dark:text-white">{{ $organizacao['nome'] }}</h3>
        </div>
    </div>
    <p class="mt-5 text-sm leading-6 text-slate-500 dark:text-slate-300">{{ $organizacao['descricao'] }}</p>
    <p class="mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400">Perfil demonstrativo</p>
    <a href="{{ route('organizacoes.show', $organizacao['id']) }}" class="mt-auto inline-flex pt-5 text-sm font-bold text-uoscc-primary hover:text-uoscc-dark dark:text-emerald-300 dark:hover:text-emerald-200" aria-label="Conhecer {{ $organizacao['nome'] }}">Conhecer organização →</a>
</article>
