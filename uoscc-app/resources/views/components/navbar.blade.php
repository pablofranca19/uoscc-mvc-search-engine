<header class="sticky top-0 z-50 border-b border-emerald-100/80 bg-white/95 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-950/95">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="UOSCC - início">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-uoscc-primary font-black text-white shadow-lg shadow-emerald-900/10">U</span>
            <span>
                <span class="block text-lg font-black tracking-tight text-uoscc-dark dark:text-uoscc-light">UOSCC</span>
                <span class="hidden text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500 sm:block dark:text-slate-400">Sociedade civil</span>
            </span>
        </a>

        <div class="flex items-center gap-2">
            <nav id="main-menu" aria-label="Navegação principal" class="absolute left-0 right-0 top-full hidden border-b border-emerald-100 bg-white p-4 shadow-lg lg:static lg:block lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none dark:border-slate-800 dark:bg-slate-950">
                <ul class="flex flex-col gap-1 lg:flex-row lg:items-center lg:gap-2">
                    @foreach([
                        ['home','Início'], ['organizacoes','Organizações'], ['eventos','Eventos'], ['espacos','Espaços'], ['apoiadores','Apoiadores']
                    ] as [$route, $label])
                        <li>
                            <a href="{{ route($route) }}" @if(request()->routeIs($route . '*')) aria-current="page" @endif @class([
                                'block rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                                'bg-emerald-50 text-uoscc-dark dark:bg-slate-800 dark:text-uoscc-light' => request()->routeIs($route . '*'),
                                'text-slate-600 hover:bg-slate-50 hover:text-uoscc-dark dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => !request()->routeIs($route . '*'),
                            ])>
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                    <li class="pt-2 lg:pl-2 lg:pt-0">
                        <a href="{{ route('eventos') }}" class="block rounded-xl bg-uoscc-primary px-5 py-2.5 text-center text-sm font-bold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-uoscc-dark">Participar</a>
                    </li>
                </ul>
            </nav>

            <button id="theme-toggle" type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Ativar modo escuro" aria-pressed="false">
                <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            </button>

            <button id="menu-button" type="button" class="hidden h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 lg:hidden dark:border-slate-700 dark:text-slate-200" aria-controls="main-menu" aria-expanded="false" aria-label="Abrir menu">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
</header>
