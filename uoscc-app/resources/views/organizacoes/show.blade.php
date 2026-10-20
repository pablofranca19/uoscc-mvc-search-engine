@extends('layouts.app')
@section('title', $organizacao['nome'].' — UOSCC')
@section('content')
<section class="bg-uoscc-dark py-14 text-white sm:py-20">
    <div class="section-shell">
        <a href="{{ route('organizacoes') }}" class="text-sm font-bold text-emerald-200">← Voltar para organizações</a>
        <div class="mt-8 flex flex-col gap-5 sm:flex-row sm:items-center">
            <div class="grid h-20 w-20 shrink-0 place-items-center rounded-3xl bg-white text-2xl font-black text-uoscc-primary" aria-hidden="true">{{ \Illuminate\Support\Str::substr($organizacao['nome'], 0, 1) }}</div>
            <div>
                <h1 class="mt-2 text-3xl font-black sm:text-4xl">{{ $organizacao['nome'] }}</h1>
                <p class="mt-3 text-emerald-100">Camaçari, Bahia</p>
            </div>
        </div>
    </div>
</section>

<section class="section-shell grid items-start gap-8 py-12 lg:grid-cols-[1fr_340px]">
    <div class="space-y-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30">
            <p class="eyebrow">Quem somos</p>
            <h2 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Sobre a organização</h2>
            <p class="mt-4 leading-8 text-slate-600 dark:text-slate-300">{{ $organizacao['descricao'] }}</p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-emerald-50 p-5 dark:bg-emerald-400/10"><strong class="text-2xl text-uoscc-dark dark:text-emerald-300">{{ count($eventosOrganizacao) }}</strong><p class="mt-1 text-sm text-slate-600 dark:text-emerald-100">eventos e projetos no catálogo</p></div>
                <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800"><strong class="text-2xl text-slate-900 dark:text-white">{{ count($espacosOrganizacao) }}</strong><p class="mt-1 text-sm text-slate-600 dark:text-slate-300">espaços no catálogo</p></div>
            </div>
        </div>

        <section aria-labelledby="organization-activities">
            <h2 id="organization-activities" class="text-2xl font-black text-slate-900 dark:text-white">Eventos e projetos</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @forelse($eventosOrganizacao as $evento)
                    <x-event-card :evento="$evento" />
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 md:col-span-2 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Esta organização ainda não tem atividades publicadas no protótipo.</p>
                        <a href="{{ route('eventos') }}" class="mt-4 inline-block text-sm font-bold text-uoscc-primary dark:text-emerald-300">Explorar atividades da rede →</a>
                    </div>
                @endforelse
            </div>
        </section>

        <section aria-labelledby="organization-spaces">
            <h2 id="organization-spaces" class="text-2xl font-black text-slate-900 dark:text-white">Espaços da organização</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @forelse($espacosOrganizacao as $espaco)
                    <x-space-card :espaco="$espaco" />
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 md:col-span-2 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Nenhum espaço publicado para esta organização.</p>
                        <a href="{{ route('espacos') }}" class="mt-4 inline-block text-sm font-bold text-uoscc-primary dark:text-emerald-300">Conhecer espaços da rede →</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <aside class="space-y-6">
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-900 dark:border-amber-500/30 dark:bg-amber-400/10 dark:text-amber-100">
            <h2 class="font-bold">Perfil demonstrativo</h2>
            <p class="mt-2">Esta organização e seus dados são exemplos fictícios. Não utilize os contatos ilustrativos para enviar mensagens.</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30">
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Contato público</h2>
            <dl class="mt-5 space-y-5 text-sm">
                <div><dt class="font-bold text-slate-900 dark:text-white">E-mail oficial</dt><dd class="mt-1 break-words text-slate-600 dark:text-slate-300">{{ $organizacao['email'] ? 'A confirmar com a equipe' : 'Ainda não informado' }}</dd></div>
                <div><dt class="font-bold text-slate-900 dark:text-white">Telefone oficial</dt><dd class="mt-1 text-slate-600 dark:text-slate-300">{{ $organizacao['telefone'] ? 'A confirmar com a equipe' : 'Ainda não informado' }}</dd></div>
            </dl>
            <h3 class="mt-8 font-bold text-slate-900 dark:text-white">Redes sociais</h3>
            @if(count($organizacao['redes_sociais']) > 0)
                <ul class="mt-3 space-y-3">
                    @foreach($organizacao['redes_sociais'] as $label => $url)
                        <li><a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-uoscc-primary dark:text-emerald-300">{{ $label }} <span class="text-xs">(abre em nova aba)</span></a></li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">Nenhuma rede social confirmada. Os links serão exibidos quando a equipe cadastrar os endereços oficiais.</p>
            @endif
        </div>
    </aside>
</section>
@endsection
