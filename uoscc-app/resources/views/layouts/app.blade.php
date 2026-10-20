<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UOSCC — Unindo forças para fortalecer organizações da sociedade civil.">
    <title>@yield('title', 'UOSCC')</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-uoscc-soft text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
    <a href="#main-content" class="fixed left-4 top-4 z-[100] -translate-y-[200%] rounded-xl bg-slate-950 px-5 py-3 font-bold text-white focus:translate-y-0">Pular para o conteúdo</a>
    <x-navbar />
    <noscript>
        <nav aria-label="Navegação sem JavaScript" class="section-shell flex flex-wrap gap-4 py-4 text-sm font-semibold text-uoscc-dark lg:hidden">
            @foreach(['home' => 'Início', 'organizacoes' => 'Organizações', 'eventos' => 'Eventos', 'espacos' => 'Espaços'] as $route => $label)
                <a href="{{ route($route) }}" class="underline underline-offset-4">{{ $label }}</a>
            @endforeach
        </nav>
    </noscript>

    <div class="border-b border-emerald-200/80 bg-gradient-to-r from-emerald-50 via-white to-teal-50 dark:border-emerald-900/50 dark:from-emerald-950/70 dark:via-slate-950 dark:to-teal-950/50">
        <div class="section-shell flex flex-col gap-3 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 text-xs leading-5 text-emerald-950 sm:text-sm dark:text-emerald-100">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-100 text-uoscc-dark shadow-sm dark:bg-emerald-400/15 dark:text-emerald-300" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2.5 2.5"/></svg>
                </span>
                <p><strong class="font-black">Consulta pública</strong><span class="hidden sm:inline"> · </span><span class="block sm:inline">Explore eventos, projetos, organizações e espaços sem cadastro.</span></p>
            </div>
            @auth
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <a href="{{ route('apoiadores') }}" class="rounded-xl bg-emerald-700 px-4 py-2 font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-200 dark:bg-emerald-500 dark:text-slate-950 dark:hover:bg-emerald-400 dark:focus:ring-emerald-900/60">Painel de {{ auth()->user()->name }}</a>
                    <form action="{{ route('apoiadores.sair') }}" method="POST">
                        @csrf
                        <button class="rounded-xl border border-emerald-300 bg-white/70 px-4 py-2 font-bold text-emerald-900 transition hover:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:border-emerald-700 dark:bg-slate-900/70 dark:text-emerald-200 dark:hover:bg-slate-800 dark:focus:ring-emerald-900/60">Sair</button>
                    </form>
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('login') }}" class="rounded-xl bg-uoscc-primary px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-uoscc-dark focus:outline-none focus:ring-4 focus:ring-emerald-200 dark:bg-emerald-400 dark:text-slate-950 dark:hover:bg-emerald-300 dark:focus:ring-emerald-900/60">Entrar como apoiador</a>
                    <a href="{{ route('organizacoes.cadastro') }}" class="rounded-xl border border-emerald-300 bg-white/75 px-4 py-2 text-sm font-bold text-emerald-900 shadow-sm transition hover:border-emerald-400 hover:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:border-emerald-600 dark:bg-slate-900/70 dark:text-emerald-200 dark:hover:bg-slate-800 dark:focus:ring-emerald-900/60">Cadastre sua OSC</a>
                </div>
            @endauth
        </div>
    </div>

    <main id="main-content" tabindex="-1">
        @if(session('demo'))
            <div class="section-shell pt-6">
                <div class="rounded-2xl border border-sky-200 bg-sky-50 p-5 text-sm leading-6 text-sky-900 dark:border-sky-900/50 dark:bg-sky-950/40 dark:text-sky-200" role="status">
                    <strong class="block">Teste do protótipo</strong>
                    {{ session('demo') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="section-shell pt-6">
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-900 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-200" role="alert" tabindex="-1" data-form-errors>
                    <p class="font-bold">Confira as informações antes de continuar:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-200" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
