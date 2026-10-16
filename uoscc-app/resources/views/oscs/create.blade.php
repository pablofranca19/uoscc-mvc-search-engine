<x-layouts.app title="Cadastrar OSC">
    <div class="mx-auto max-w-4xl">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/50">
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-600 px-6 py-8 text-white sm:px-10 sm:py-10">
                <div class="absolute -right-12 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full border border-white/15"></div>

                <div class="relative max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-100">Nova organização</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Cadastre uma OSC</h1>
                    <p class="mt-3 max-w-xl text-base leading-7 text-blue-50 sm:text-lg">Adicione os dados essenciais para tornar a organização fácil de encontrar e contatar.</p>
                </div>
            </div>

            <form class="p-6 sm:p-10" method="POST" action="{{ route('oscs.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="mb-8 flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-8-4a1 1 0 0 0-.894.553l-3 6A1 1 0 0 0 7 14h6a1 1 0 0 0 .894-1.447l-3-6A1 1 0 0 0 10 6Zm0 2a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 10 8Zm0 4.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Z" clip-rule="evenodd" /></svg>
                        <div><p class="font-semibold">Confira os dados informados.</p><p class="mt-1 text-red-700">Os campos destacados precisam de atenção antes de salvar.</p></div>
                    </div>
                @endif

                @include('oscs._form')

                <div class="mt-10 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500"><span class="font-semibold text-red-600">*</span> Campos obrigatórios</p>
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center">
                        <a class="rounded-xl px-4 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-100" href="{{ route('oscs.index') }}">Cancelar</a>
                        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-blue-700/30 focus:outline-none focus:ring-4 focus:ring-blue-200" type="submit">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10.75 2.5a.75.75 0 0 0-1.5 0v6.75H2.5a.75.75 0 0 0 0 1.5h6.75v6.75a.75.75 0 0 0 1.5 0v-6.75h6.75a.75.75 0 0 0 0-1.5h-6.75V2.5Z" /></svg>
                            Cadastrar organização
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
