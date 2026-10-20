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

    <div class="border-b border-emerald-100 bg-emerald-50/70 dark:border-emerald-900/40 dark:bg-emerald-950/40">
        <div class="section-shell flex flex-wrap items-center justify-between gap-2 py-3 text-xs leading-5 text-emerald-900 sm:text-sm dark:text-emerald-200">
            <p><strong>Consulta pública</strong> · Explore eventos, projetos, organizações e espaços sem cadastro.</p>
            <span class="font-bold">Encontre iniciativas e espaços para participar.</span>
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
