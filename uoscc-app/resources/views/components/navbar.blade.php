<header class="sticky top-0 z-50 border-b border-emerald-100/80 bg-white/95 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-950/95">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="UOSCC - início">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-uoscc-primary to-uoscc-dark text-white shadow-lg shadow-emerald-900/20 transition-transform duration-300 hover:scale-105 hover:rotate-3">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="12" r="5.5"/>
                    <circle cx="15" cy="12" r="5.5"/>
                    <circle cx="12" cy="12" r="1.2" fill="currentColor" stroke="none"/>
                </svg>
            </span>
            <span>
                <span class="block text-lg font-black tracking-tight text-uoscc-dark dark:text-uoscc-light">MyREDCAM</span>
                <span class="hidden text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500 sm:block dark:text-slate-400">Sociedade civil</span>
            </span>
        </a>

        <div class="flex items-center gap-2">
            <button id="theme-toggle" type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Ativar modo escuro" aria-pressed="false">
                <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            </button>

            <button id="menu-button" type="button" class="group grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" aria-controls="main-menu" aria-expanded="false" aria-label="Abrir menu">
                <span class="relative block h-4 w-5">
                    <span class="absolute left-0 top-0 h-0.5 w-full rounded-full bg-current transition-all duration-300 group-aria-expanded:translate-y-[7px] group-aria-expanded:rotate-45"></span>
                    <span class="absolute left-0 top-1/2 h-0.5 w-full -translate-y-1/2 rounded-full bg-current transition-all duration-300 group-aria-expanded:opacity-0"></span>
                    <span class="absolute bottom-0 left-0 h-0.5 w-full rounded-full bg-current transition-all duration-300 group-aria-expanded:-translate-y-[7px] group-aria-expanded:-rotate-45"></span>
                </span>
            </button>
        </div>
    </div>
</header>

<div id="menu-overlay" class="invisible fixed inset-0 z-40 bg-slate-950/50 opacity-0 backdrop-blur-sm transition-opacity duration-300 lg:bg-slate-950/60" aria-hidden="true"></div>

<nav id="main-menu" aria-label="Navegação principal" class="fixed right-0 top-0 z-50 flex h-full w-80 max-w-[85vw] translate-x-full flex-col border-l border-emerald-100 bg-white p-6 shadow-2xl transition-transform duration-300 ease-out dark:border-slate-800 dark:bg-slate-900">
    <div class="mb-6 flex items-center justify-between">
        <span class="text-lg font-black text-uoscc-dark dark:text-uoscc-light">Menu</span>
        <button type="button" data-menu-close class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Fechar menu">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <ul class="flex flex-col gap-1">
        @foreach([
            ['home','Início'], ['organizacoes','Organizações'], ['eventos','Eventos'], ['espacos','Espaços'], ['apoiadores','Apoiadores']
        ] as [$route, $label])
            <li>
                <a href="{{ route($route) }}" @if(request()->routeIs($route . '*')) aria-current="page" @endif @class([
                    'block rounded-xl px-4 py-3 text-base font-semibold transition',
                    'bg-emerald-50 text-uoscc-dark dark:bg-slate-800 dark:text-uoscc-light' => request()->routeIs($route . '*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-uoscc-dark dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => !request()->routeIs($route . '*'),
                ])>
                    {{ $label }}
                </a>
            </li>
        @endforeach
        @auth
            <li>
                <a href="{{ route('users.show', auth()->id()) }}" @if(request()->routeIs('users.show')) aria-current="page" @endif @class([
                    'block rounded-xl px-4 py-3 text-base font-semibold transition',
                    'bg-emerald-50 text-uoscc-dark dark:bg-slate-800 dark:text-uoscc-light' => request()->routeIs('users.show'),
                    'text-slate-600 hover:bg-slate-50 hover:text-uoscc-dark dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('users.show'),
                ])>
                    Minha conta
                </a>
            </li>
        @endauth
    </ul>

    <a href="{{ route('eventos') }}" class="mt-6 block rounded-xl bg-uoscc-primary px-5 py-3 text-center text-sm font-bold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-uoscc-dark">Participar</a>
</nav>
