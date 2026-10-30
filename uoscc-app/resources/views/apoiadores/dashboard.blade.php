@extends('layouts.app')

@section('title', 'Painel do apoiador')

@section('content')
    <section class="bg-emerald-50 py-14 text-slate-900 dark:bg-slate-950 dark:text-white sm:py-20">
        <div class="section-shell">
            <p class="eyebrow text-emerald-300">Área do apoiador</p>
            <h1 class="mt-3 max-w-3xl text-3xl font-black sm:text-5xl">Seu impacto, em um só lugar.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">Salve iniciativas, acompanhe participações, encontre oportunidades compatíveis e consulte seus certificados.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a class="rounded-xl border border-emerald-300 px-5 py-3 text-sm font-bold text-emerald-900 hover:bg-white dark:border-white/30 dark:text-white dark:hover:bg-white/10" href="{{ route('apoiadores.reservas') }}">Minhas reservas de espaços</a>
                @if (auth()->user()->isOrganizationManager())
                    <a class="rounded-xl border border-emerald-300 px-5 py-3 text-sm font-bold text-emerald-900 hover:bg-white dark:border-white/30 dark:text-white dark:hover:bg-white/10" href="{{ route('eventos.create') }}">Cadastrar evento ou projeto</a>
                @endif
            </div>
        </div>
    </section>

    <section class="section-shell grid gap-8 py-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(300px,.9fr)]">
        <div class="space-y-8">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="eyebrow">Perfil de apoiador</p>
                        <h2 class="mt-2 text-2xl font-black">Habilidades, interesses e disponibilidade</h2>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">{{ $profile['name'] !== '' ? 'Perfil ativo' : 'Complete seu perfil' }}</span>
                </div>
                <form action="{{ route('apoiadores.perfil') }}" method="POST" class="mt-6 grid gap-5 sm:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="label" for="supporter-name">Nome completo</label>
                        <input id="supporter-name" class="input" name="name" value="{{ old('name', $profile['name']) }}" maxlength="120" required>
                    </div>
                    <div>
                        <label class="label" for="supporter-email">E-mail</label>
                        <input id="supporter-email" class="input" type="email" name="email" value="{{ old('email', $profile['email']) }}" maxlength="254" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label" for="supporter-skills">Habilidades</label>
                        <input id="supporter-skills" class="input" name="skills" value="{{ old('skills', $profile['skills']) }}" maxlength="1000" placeholder="Ex.: comunicação, fotografia, oficina de informática">
                    </div>
                    <div>
                        <label class="label" for="supporter-interests">Interesses em voluntariado</label>
                        <input id="supporter-interests" class="input" name="interests" value="{{ old('interests', $profile['interests']) }}" maxlength="1000" placeholder="Ex.: educação, cultura, meio ambiente">
                    </div>
                    <div>
                        <label class="label" for="supporter-availability">Disponibilidade</label>
                        <input id="supporter-availability" class="input" name="availability" value="{{ old('availability', $profile['availability']) }}" maxlength="500" placeholder="Ex.: sábados pela manhã">
                    </div>
                    <div class="sm:col-span-2">
                        <button class="rounded-xl bg-uoscc-primary px-5 py-3 font-bold text-white hover:bg-uoscc-dark">Salvar perfil</button>
                    </div>
                </form>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="eyebrow">Oportunidades</p>
                <h2 class="mt-2 text-2xl font-black">Inscrição simplificada</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Depois de completar o perfil, inscreva-se sem digitar nome e contato novamente.</p>
                <div class="mt-6 grid gap-4">
                    @foreach($activities as $key => $activity)
                        @php([$type, $id] = explode(':', $key))
                        <article class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary">{{ $activity['type'] }} · {{ $activity['area'] }}</p>
                                    <h3 class="mt-1 text-lg font-black">{{ $activity['title'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $activity['organization'] }} · {{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('apoiadores.favoritos', [$type, $id]) }}" method="POST">
                                        @csrf
                                        <button class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold hover:bg-slate-50 dark:border-slate-600 dark:hover:bg-slate-800">{{ isset($favorites[$key]) ? 'Remover favorito' : 'Favoritar' }}</button>
                                    </form>
                                    <form action="{{ route('apoiadores.inscricoes', [$type, $id]) }}" method="POST">
                                        @csrf
                                        <button class="rounded-xl bg-uoscc-primary px-4 py-2 text-sm font-bold text-white hover:bg-uoscc-dark">{{ isset($registrations[$key]) ? 'Inscrição confirmada' : 'Inscrever-se' }}</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="eyebrow">Favoritos</p>
                <h2 class="mt-2 text-2xl font-black">Organizações e iniciativas salvas</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach($organizations as $key => $organization)
                        @php([$type, $id] = explode(':', $key))
                        <article class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                            <p class="text-xs font-bold uppercase tracking-wider text-uoscc-primary">OSC · {{ $organization['area'] }}</p>
                            <h3 class="mt-2 font-black">{{ $organization['name'] }}</h3>
                            <form action="{{ route('apoiadores.favoritos', [$type, $id]) }}" method="POST" class="mt-4">
                                @csrf
                                <button class="text-sm font-bold text-uoscc-primary underline underline-offset-4">{{ isset($favorites[$key]) ? 'Remover dos favoritos' : 'Salvar como favorita' }}</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        <aside class="space-y-8">
            <section class="rounded-3xl bg-uoscc-dark p-6 text-white shadow-lg">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-200">Notificações</p>
                <h2 class="mt-2 text-2xl font-black">O que há de novo</h2>
                @forelse($notifications as $notification)
                    <p class="mt-4 rounded-2xl bg-white/10 p-4 text-sm leading-6 text-slate-100">{{ $notification }}</p>
                @empty
                    <p class="mt-4 text-sm leading-6 text-slate-200">Favorite uma OSC ou informe seus interesses para receber oportunidades compatíveis aqui.</p>
                @endforelse
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="eyebrow">Painel pessoal</p>
                <h2 class="mt-2 text-2xl font-black">Atividades e certificados</h2>
                <div class="mt-6 space-y-4">
                    @forelse($registrations as $key => $registration)
                        @php([$type, $id] = explode(':', $key))
                        <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="font-bold">{{ $registration['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $registration['status'] }}</p>
                            @if(isset($registration['completed_at']))
                                <a href="{{ route('apoiadores.certificados', [$type, $id]) }}" class="mt-3 inline-block text-sm font-bold text-uoscc-primary underline underline-offset-4">Consultar certificado</a>
                            @else
                                <form action="{{ route('apoiadores.participacoes.confirmar', [$type, $id]) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button class="text-sm font-bold text-uoscc-primary underline underline-offset-4">Confirmar participação</button>
                                </form>
                            @endif
                        </article>
                    @empty
                        <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Você ainda não possui inscrições. Escolha uma oportunidade para começar.</p>
                    @endforelse
                </div>
            </section>
        </aside>
    </section>
@endsection
