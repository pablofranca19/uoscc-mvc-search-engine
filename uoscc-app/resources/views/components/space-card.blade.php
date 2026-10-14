@props(['espaco'])
<article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    <div class="h-32 bg-gradient-to-br from-slate-800 to-uoscc-dark p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-200">{{ $espaco['organizacao'] }}</p>
        <h3 class="mt-2 text-xl font-extrabold">{{ $espaco['nome'] }}</h3>
    </div>
    <div class="p-6">
        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">Até {{ $espaco['capacidade'] }} pessoas</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $espaco['regiao'] }}</span>
        </div>
        <p class="mt-4 text-sm leading-6 text-slate-500">{{ $espaco['descricao'] }}</p>
        <a href="{{ route('espacos.show', $espaco['id']) }}" class="mt-5 block rounded-xl bg-uoscc-primary px-4 py-3 text-center text-sm font-bold text-white hover:bg-uoscc-dark">Ver espaço e solicitar reserva</a>
    </div>
</article>
