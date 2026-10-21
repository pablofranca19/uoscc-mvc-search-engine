@extends('layouts.app')
@section('title', 'Acesso de apoiadores — UOSCC')
@section('content')
<section class="section-shell py-14 sm:py-20">
    <p class="eyebrow">Faça parte da rede</p>
    <h1 class="section-title">Apoiar começa com uma conexão</h1>
    <p class="section-copy">Quer se aproximar das organizações e contribuir com a comunidade? Conheça a proposta da área de apoiadores da UOSCC.</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="#como-participar" class="rounded-xl bg-uoscc-primary px-5 py-3 text-sm font-bold text-white transition hover:bg-uoscc-dark">Como participar</a>
        <a href="{{ route('organizacoes') }}" class="rounded-xl border border-emerald-200 px-5 py-3 text-sm font-bold text-uoscc-dark transition hover:bg-emerald-50 dark:border-slate-700 dark:text-uoscc-light dark:hover:bg-slate-800">Conhecer as organizações</a>
    </div>
    <aside class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-950 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-100" aria-label="Situação do protótipo">
        <p class="font-bold">Uma proposta em construção</p>
        <p class="mt-1">Estamos preparando esta experiência. As regras de participação e os conteúdos exclusivos ainda precisam ser aprovados pela equipe. Nenhuma solicitação de acesso é recebida nesta etapa.</p>
    </aside>
    <section class="mt-12" aria-labelledby="who-can-support-title">
        <h2 id="who-can-support-title" class="text-2xl font-black">Para quem quer caminhar junto</h2>
        <p class="mt-3 max-w-3xl leading-7 text-slate-600 dark:text-slate-300">A proposta contempla pessoas, empresas e instituições interessadas em apoiar as organizações. Formas de colaboração, critérios de cadastro e permissões serão definidos com a UOSCC. Você pode começar conhecendo as iniciativas públicas, sem criar uma conta.</p>
    </section>
    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <section class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm sm:p-8 dark:border-emerald-900/50 dark:bg-slate-900" aria-labelledby="public-access-title">
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">Disponível · sem cadastro</span>
            <h2 id="public-access-title" class="mt-5 text-2xl font-black">Consulta pública</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">Qualquer pessoa pode conhecer as iniciativas da rede e consultar informações publicadas pelas organizações.</p>
            <ul class="mt-6 space-y-4 text-sm font-semibold text-uoscc-dark dark:text-uoscc-light">
                <li><a href="{{ route('eventos') }}" class="underline underline-offset-4">Eventos, projetos, prazos e vagas →</a></li>
                <li><a href="{{ route('espacos') }}" class="underline underline-offset-4">Espaços, capacidade e estrutura →</a></li>
                <li><a href="{{ route('organizacoes') }}" class="underline underline-offset-4">Perfis e contatos públicos das OSCs →</a></li>
            </ul>
            <p class="mt-6 text-sm leading-6 text-slate-500 dark:text-slate-400">Os formulários de inscrição e de reserva continuam em demonstração e não registram pedidos.</p>
        </section>
        <section class="rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-8 dark:border-slate-800 dark:bg-slate-900" aria-labelledby="supporter-access-title">
            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">Área restrita · em preparação</span>
            <h2 id="supporter-access-title" class="mt-5 text-2xl font-black">Um espaço para apoiadores</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">O grupo ainda definirá quais conteúdos serão exclusivos para apoiadores e quem poderá acessá-los.</p>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">Não há login, cadastro ou conteúdo privado disponível nesta versão. O acesso dependerá de autenticação e permissões quando essa etapa for implementada.</p>
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-800">
                <h3 class="font-bold">O que você pode fazer agora?</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Explore as informações públicas e conheça as organizações. Nenhuma conta é necessária para consultar.</p>
            </div>
        </section>
    </div>
    <section id="como-participar" class="mt-14 scroll-mt-28" aria-labelledby="participation-title">
        <p class="eyebrow">Como participar</p>
        <h2 id="participation-title" class="mt-2 text-2xl font-black">Um caminho para se aproximar</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Fluxo proposto para validação com a equipe. As etapas de cadastro e aprovação ainda não estão disponíveis.</p>
        <ol class="mt-6 grid gap-5 md:grid-cols-3">
            <li class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                <span class="text-sm font-bold text-uoscc-dark dark:text-uoscc-light">01 · Conheça a rede</span>
                <h3 class="mt-3 text-lg font-bold">Encontre uma causa próxima de você</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Explore os perfis das organizações, suas atividades e os espaços da comunidade.</p>
                <a href="{{ route('organizacoes') }}" class="mt-4 inline-block text-sm font-bold text-uoscc-dark underline underline-offset-4 dark:text-uoscc-light">Explorar organizações →</a>
            </li>
            <li class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">02 · Etapa futura</span>
                <h3 class="mt-3 text-lg font-bold">Solicite seu acesso</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">A proposta é ter um formulário para apresentar seu interesse em apoiar a rede. Os dados necessários ainda serão definidos.</p>
            </li>
            <li class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">03 · Regra em avaliação</span>
                <h3 class="mt-3 text-lg font-bold">Receba uma orientação da equipe</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Sugerimos que a equipe avalie as solicitações antes de liberar o acesso. Quem aprova e como a resposta será enviada ainda serão confirmados.</p>
            </li>
        </ol>
    </section>
    <section class="mt-14" aria-labelledby="supporter-questions-title">
        <h2 id="supporter-questions-title" class="text-2xl font-black">Dúvidas sobre o acesso</h2>
        <div class="mt-6 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white px-5 dark:divide-slate-800 dark:border-slate-800 dark:bg-slate-900">
            <details class="py-5">
                <summary class="cursor-pointer font-bold">Preciso de cadastro para conhecer eventos e espaços?</summary>
                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Não. Eventos, projetos, espaços e perfis públicos podem ser consultados livremente. Você não precisa ser apoiador para conhecer a rede.</p>
            </details>
            <details class="py-5">
                <summary class="cursor-pointer font-bold">Já posso criar uma conta de apoiador?</summary>
                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Ainda não. Esta etapa apresenta o funcionamento proposto. As telas de acesso e cadastro serão desenvolvidas nas próximas etapas.</p>
            </details>
            <details class="py-5">
                <summary class="cursor-pointer font-bold">O que estará disponível na área restrita?</summary>
                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Estamos propondo um painel, oportunidades exclusivas e um perfil do apoiador. O conteúdo e as permissões precisam ser validados pela equipe antes de serem oferecidos.</p>
            </details>
            <details class="py-5">
                <summary class="cursor-pointer font-bold">Ser apoiador garante uma vaga ou reserva?</summary>
                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Esta proposta não oferece prioridade ou garantia de vagas. Inscrições e reservas têm regras próprias e, no protótipo atual, os formulários apenas simulam o envio.</p>
            </details>
        </div>
    </section>
</section>
@endsection
