<footer class="mt-20 border-t border-emerald-100 bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-uoscc-primary font-black text-white">U</span>
                <span class="text-lg font-black text-uoscc-dark">UOSCC</span>
            </div>
            <p class="mt-4 max-w-sm text-sm leading-6 text-slate-500">Unindo forças para fortalecer organizações da sociedade civil e aproximar projetos, pessoas e oportunidades.</p>
        </div>
        <div>
            <h3 class="font-bold text-slate-900">Navegação</h3>
            <div class="mt-4 grid gap-2 text-sm text-slate-500">
                <a class="hover:text-uoscc-primary" href="{{ route('home') }}">Início</a>
                <a class="hover:text-uoscc-primary" href="{{ route('organizacoes') }}">Organizações</a>
                <a class="hover:text-uoscc-primary" href="{{ route('eventos') }}">Eventos</a>
                <a class="hover:text-uoscc-primary" href="{{ route('espacos') }}">Espaços</a>
                <a class="hover:text-uoscc-primary" href="{{ route('apoiadores') }}">Informações para apoiadores</a>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-slate-900">Contato</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-500">
                <p>Contato oficial a confirmar com a equipe.</p>
                <p>Os canais serão publicados após validação.</p>
                <p>Camaçari — Bahia</p>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">© {{ date('Y') }} UOSCC. Todos os direitos reservados.</div>
</footer>
