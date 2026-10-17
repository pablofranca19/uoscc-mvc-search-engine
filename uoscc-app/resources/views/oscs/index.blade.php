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
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a class="font-medium text-blue-700 hover:underline" href="{{ route('oscs.edit', $osc) }}">Editar</a>
                                <form class="ml-3 inline" method="POST" action="{{ route('oscs.destroy', $osc) }}" onsubmit="return confirm('Deseja excluir esta OSC?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-medium text-red-700 hover:underline" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
