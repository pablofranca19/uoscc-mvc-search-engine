@props(['organizacao'])
<article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    <div class="flex items-start gap-4">
        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-emerald-50 font-black text-uoscc-primary" aria-hidden="true">{{ \Illuminate\Support\Str::substr($organizacao['nome'], 0, 1) }}</div>
        <div>
            <h3 class="mt-1 font-extrabold text-slate-900">{{ $organizacao['nome'] }}</h3>
        </div>
    </div>
    <p class="mt-5 text-sm leading-6 text-slate-500">{{ $organizacao['descricao'] }}</p>
    <p class="mt-4 text-xs font-semibold text-slate-500">Perfil demonstrativo</p>
    <a href="{{ route('organizacoes.show', $organizacao['id']) }}" class="mt-auto inline-flex pt-5 text-sm font-bold text-uoscc-primary hover:text-uoscc-dark" aria-label="Conhecer {{ $organizacao['nome'] }}">Conhecer organização →</a>
</article>
