<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Gestão de OSCs' }}</title>
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <header class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
                <a class="text-xl font-bold text-slate-800 dark:text-white" href="{{ url('/') }}">UOSCC</a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-emerald-500/30 dark:bg-emerald-400/10 dark:text-emerald-100" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            {{ $slot }}
        </main>
    </body>
</html>
