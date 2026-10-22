<nav aria-label="Área de apoiadores" class="mb-8 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm font-bold dark:border-slate-800 dark:bg-slate-900">
    <a href="{{ route('apoiadores.acesso') }}" class="rounded-xl px-3 py-2 text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Minha área</a>
    @if(auth()->user()->hasApprovedSupporterAccess())
        @foreach(['apoiadores.painel' => 'Painel', 'apoiadores.oportunidades' => 'Oportunidades', 'apoiadores.perfil' => 'Meu perfil'] as $route => $label)
            <a href="{{ route($route) }}" @if(request()->routeIs($route)) aria-current="page" @endif @class(['rounded-xl px-3 py-2 hover:bg-emerald-50 dark:hover:bg-slate-800', 'bg-emerald-50 text-uoscc-dark dark:bg-slate-800 dark:text-uoscc-light' => request()->routeIs($route)])>{{ $label }}</a>
        @endforeach
    @endif
    @can('review-supporters')<a href="{{ route('apoiadores.admin') }}" class="rounded-xl px-3 py-2 underline underline-offset-4">Analisar solicitações</a>@endcan
    <form action="{{ route('apoiadores.logout') }}" method="POST" class="sm:ml-auto">@csrf<button class="rounded-xl border border-slate-200 px-4 py-2 dark:border-slate-700">Sair da conta</button></form>
</nav>
