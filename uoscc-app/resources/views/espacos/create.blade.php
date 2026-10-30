@extends('layouts.app')

@section('title', 'Cadastrar espaço')

@section('content')
    <section class="section-shell py-14 sm:py-20">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">Área da organização</p>
            <h1 class="mt-2 text-3xl font-black sm:text-4xl">Cadastrar espaço</h1>
            <p class="section-copy">Informe onde a OSC realiza atividades para associar o espaço aos próximos eventos e projetos.</p>

            <form action="{{ route('espacos.store') }}" method="POST" class="mt-8 grid gap-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2 sm:p-8 dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/30">
                @csrf
                <div class="sm:col-span-2">
                    <label class="label" for="space-name">Nome do espaço</label>
                    <input id="space-name" class="input" name="name" value="{{ old('name') }}" maxlength="255" placeholder="Ex.: Sala de oficinas" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="space-description">Descrição</label>
                    <textarea id="space-description" class="input min-h-32" name="description" minlength="10" placeholder="Descreva a estrutura e os usos do espaço." required>{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="label" for="space-capacity">Capacidade</label>
                    <input id="space-capacity" class="input" name="capacity" type="number" min="1" value="{{ old('capacity') }}" placeholder="Ex.: 30" required>
                </div>
                <fieldset>
                    <legend class="label">Estrutura disponível</legend>
                    <div class="grid grid-cols-2 gap-2 text-sm text-slate-700 dark:text-slate-300">
                        @foreach(['Wi-Fi', 'Projetor', 'Ar-condicionado', 'Banheiro acessível', 'Estacionamento', 'Cozinha'] as $facility)
                            <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 dark:border-slate-700">
                                <input type="checkbox" name="facilities[]" value="{{ $facility }}" @checked(in_array($facility, old('facilities', [])))>
                                {{ $facility }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="sm:col-span-2"><p class="text-sm font-black text-slate-900 dark:text-white">Endereço do espaço</p></div>
                <div>
                    <label class="label" for="space-cep">CEP</label>
                    <input id="space-cep" class="input" name="cep" value="{{ old('cep') }}" minlength="8" maxlength="9" placeholder="Ex.: 42800-000" required>
                </div>
                <div>
                    <label class="label" for="space-neighbourhood">Bairro</label>
                    <input id="space-neighbourhood" class="input" name="neighbourhood" value="{{ old('neighbourhood') }}" maxlength="255" required>
                </div>
                <div>
                    <label class="label" for="space-street">Rua ou avenida</label>
                    <input id="space-street" class="input" name="street" value="{{ old('street') }}" maxlength="255" required>
                </div>
                <div>
                    <label class="label" for="space-number">Número</label>
                    <input id="space-number" class="input" name="number" type="number" min="0" value="{{ old('number') }}" required>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:col-span-2 sm:flex-row sm:justify-end dark:border-slate-700">
                    <a class="rounded-xl px-5 py-3 text-center text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800" href="{{ route('organizacoes.perfil') }}">Cancelar</a>
                    <button class="rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white hover:bg-uoscc-dark">Cadastrar espaço</button>
                </div>
            </form>
        </div>
    </section>
@endsection
