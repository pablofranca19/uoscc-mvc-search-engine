<x-layouts.app title="OSCs cadastradas">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">OSCs cadastradas</h1>
            <p class="mt-1 text-slate-600">Gerencie as organizações da sociedade civil.</p>
        </div>
        <a class="rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800" href="{{ route('oscs.create') }}">Cadastrar OSC</a>
    </div>

    @if ($oscs->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
            <p class="font-medium">Nenhuma OSC cadastrada.</p>
            <a class="mt-3 inline-block text-blue-700 underline" href="{{ route('oscs.create') }}">Cadastrar a primeira OSC</a>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nome</th>
                        <th class="px-4 py-3 font-semibold">Área de atuação</th>
                        <th class="px-4 py-3 font-semibold">Contato</th>
                        <th class="px-4 py-3 font-semibold"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($oscs as $osc)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $osc->nome }}</td>
                            <td class="px-4 py-3">{{ $osc->area_atuacao }}</td>
                            <td class="px-4 py-3">
                                <div>{{ $osc->email ?: '—' }}</div>
                                <div class="text-slate-500">{{ $osc->telefone }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200" href="{{ route('atividades.create', ['osc_id' => $osc->id]) }}">Adicionar evento</a>
                                    <a class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-100" href="{{ route('oscs.edit', $osc) }}">Editar</a>
                                    <form method="POST" action="{{ route('oscs.destroy', $osc) }}" onsubmit="return confirm('Deseja excluir esta OSC?');">
                                    @csrf
                                    @method('DELETE')
                                        <button class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-100" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
