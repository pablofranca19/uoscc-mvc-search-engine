<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UOSCC — Unindo forças para fortalecer organizações da sociedade civil.">
    <title>@yield('title', 'UOSCC')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-uoscc-soft text-slate-800 antialiased">
    <x-navbar />

    <main>
        @if(session('demo'))
            <div class="section-shell pt-6">
                <div class="rounded-2xl border border-sky-200 bg-sky-50 p-5 text-sm leading-6 text-sky-900" role="status">
                    <strong class="block">Teste do protótipo</strong>
                    {{ session('demo') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="section-shell pt-6">
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-900" role="alert">
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
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
