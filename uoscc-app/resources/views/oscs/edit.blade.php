<x-layouts.app title="Editar OSC">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Editar OSC</h1>
        <p class="mt-1 text-slate-600">Atualize os dados de {{ $osc->nome }}.</p>
    </div>

    <form class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm" method="POST" action="{{ route('oscs.update', $osc) }}">
        @csrf
        @method('PUT')
        @include('oscs._form')

        <div class="mt-6 flex gap-3">
            <button class="rounded-md bg-blue-700 px-4 py-2 font-semibold text-white hover:bg-blue-800" type="submit">Salvar alterações</button>
            <a class="rounded-md border border-slate-300 px-4 py-2 font-semibold hover:bg-slate-100" href="{{ route('oscs.index') }}">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
