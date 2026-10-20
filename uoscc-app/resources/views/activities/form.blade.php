<x-layouts.app title="{{ $activity->exists ? 'Editar atividade' : 'Criar atividade' }}">
    @php
        $isEditing = $activity->exists;
        $activityDate = $activity->data
            ? \Illuminate\Support\Carbon::parse($activity->data)->toDateString()
            : '';
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        <div class="mb-8">
            <p class="text-sm font-semibold text-emerald-700">Gestão da OSC</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                {{ $isEditing ? 'Editar evento ou projeto' : 'Criar evento ou projeto' }}
            </h1>
            <p class="mt-2 text-slate-600">
                Preencha os dados para que a atividade apareça para os visitantes da plataforma.
            </p>
        </div>

        <form class="space-y-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" method="POST" action="{{ $isEditing ? route('atividades.update', $activity) : route('atividades.store') }}">
            @csrf
            @if ($isEditing)
                @method('PUT')
            @endif

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="osc_id">OSC responsável <span class="text-red-600">*</span></label>
                    <select class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="osc_id" name="osc_id" required>
                        <option value="">Selecione a OSC</option>
                        @foreach ($oscs as $osc)
                            <option value="{{ $osc->id }}" @selected(old('osc_id', $activity->osc_id) == $osc->id)>{{ $osc->nome }}</option>
                        @endforeach
                    </select>
                    @error('osc_id')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="tipo">Tipo <span class="text-red-600">*</span></label>
                    <select class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="tipo" name="tipo" required>
                        <option value="evento" @selected(old('tipo', $activity->tipo) === 'evento')>Evento</option>
                        <option value="projeto" @selected(old('tipo', $activity->tipo) === 'projeto')>Projeto</option>
                    </select>
                    @error('tipo')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-800" for="titulo">Título <span class="text-red-600">*</span></label>
                    <input class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="titulo" name="titulo" placeholder="Ex.: Oficina de xadrez para iniciantes" required type="text" value="{{ old('titulo', $activity->titulo) }}">
                    @error('titulo')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="data">Data <span class="text-red-600">*</span></label>
                    <input class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="data" name="data" required type="date" value="{{ old('data', $activityDate) }}">
                    @error('data')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="vagas">Quantidade de vagas <span class="text-red-600">*</span></label>
                    <input class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="vagas" min="0" name="vagas" required type="number" value="{{ old('vagas', $activity->vagas ?? 0) }}">
                    @error('vagas')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="local">Local <span class="text-red-600">*</span></label>
                    <input class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="local" name="local" placeholder="Ex.: Praça da Esperança" required type="text" value="{{ old('local', $activity->local) }}">
                    @error('local')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="regiao">Região</label>
                    <input class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="regiao" name="regiao" placeholder="Ex.: Camaçari — Orla" type="text" value="{{ old('regiao', $activity->regiao) }}">
                    @error('regiao')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-800" for="descricao">Descrição <span class="text-red-600">*</span></label>
                    <textarea class="mt-2 block w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100" id="descricao" name="descricao" placeholder="Explique o objetivo da atividade e quem pode participar." required rows="5">{{ old('descricao', $activity->descricao) }}</textarea>
                    @error('descricao')<p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                <a class="rounded-xl px-5 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100" href="{{ route('oscs.index') }}">Cancelar</a>
                <button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200" type="submit">
                    {{ $isEditing ? 'Salvar alterações' : 'Publicar atividade' }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
