<footer class="mt-20 border-t border-emerald-100 bg-white dark:border-slate-800 dark:bg-slate-900">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-uoscc-primary to-uoscc-dark text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="12" r="5.5"/>
                        <circle cx="15" cy="12" r="5.5"/>
                        <circle cx="12" cy="12" r="1.2" fill="currentColor" stroke="none"/>
                    </svg>
                </span>
                <span class="text-lg font-black text-uoscc-dark dark:text-uoscc-light">UOSCC</span>
            </div>
            <p class="mt-4 max-w-sm text-sm leading-6 text-slate-500 dark:text-slate-400">Unindo forças para fortalecer organizações da sociedade civil e aproximar projetos, pessoas e oportunidades.</p>
        </div>
        <div>
            <h3 class="font-bold text-slate-900 dark:text-white">Navegação</h3>
            <div class="mt-4 grid gap-2 text-sm text-slate-500 dark:text-slate-400">
                <a class="hover:text-uoscc-primary dark:hover:text-uoscc-light" href="{{ route('home') }}">Início</a>
                <a class="hover:text-uoscc-primary dark:hover:text-uoscc-light" href="{{ route('organizacoes') }}">Organizações</a>
                <a class="hover:text-uoscc-primary dark:hover:text-uoscc-light" href="{{ route('eventos') }}">Eventos</a>
                <a class="hover:text-uoscc-primary dark:hover:text-uoscc-light" href="{{ route('espacos') }}">Espaços</a>
                <span>Informações para apoiadores em breve</span>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-slate-900 dark:text-white">Contato</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                <p>Contato oficial a confirmar com a equipe.</p>
                <p>Os canais serão publicados após validação.</p>
                <p>Camaçari — Bahia</p>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400 dark:border-slate-800 dark:text-slate-500">© {{ date('Y') }} UOSCC. Todos os direitos reservados.</div>
</footer>
