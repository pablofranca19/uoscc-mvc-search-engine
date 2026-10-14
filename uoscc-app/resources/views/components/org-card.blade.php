@props(['organizacao'])
<article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    <div class="flex items-start gap-4">
        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-emerald-50 font-black text-uoscc-primary">{{ $organizacao['sigla'] }}</div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary">{{ $organizacao['area'] }}</p>
            <h3 class="mt-1 font-extrabold text-slate-900">{{ $organizacao['nome'] }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $organizacao['regiao'] }}</p>
        </div>
    </div>
    <p class="mt-5 text-sm leading-6 text-slate-500">{{ $organizacao['descricao'] }}</p>
    <a href="{{ route('organizacoes.show', $organizacao['id']) }}" class="mt-5 inline-flex text-sm font-bold text-uoscc-primary hover:text-uoscc-dark">Conhecer organização →</a>
</article>
