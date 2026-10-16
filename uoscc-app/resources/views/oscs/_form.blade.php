<div class="space-y-10">
    <section aria-labelledby="dados-organizacao">
        <div class="mb-6">
            <p class="text-sm font-semibold text-blue-700">Etapa 1 de 2</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900" id="dados-organizacao">Sobre a organização</h2>
            <p class="mt-1 text-sm leading-6 text-slate-500">Informe como a OSC será apresentada na plataforma.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800" for="nome">Nome da OSC <span class="text-red-600">*</span></label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('nome'), 'border-slate-300' => ! $errors->has('nome')]) aria-describedby="nome-ajuda @error('nome') nome-erro @enderror" aria-invalid="@error('nome') true @else false @enderror" id="nome" name="nome" placeholder="Ex.: Instituto Caminhos Abertos" required type="text" value="{{ old('nome', $osc->nome ?? '') }}">
                <p class="mt-2 text-xs text-slate-500" id="nome-ajuda">Use o nome pelo qual a organização é conhecida.</p>
                @error('nome')<p class="mt-2 text-sm font-medium text-red-700" id="nome-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800" for="area_atuacao">Área de atuação <span class="text-red-600">*</span></label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('area_atuacao'), 'border-slate-300' => ! $errors->has('area_atuacao')]) aria-describedby="area-ajuda @error('area_atuacao') area-erro @enderror" aria-invalid="@error('area_atuacao') true @else false @enderror" id="area_atuacao" name="area_atuacao" placeholder="Ex.: Educação e cidadania" required type="text" value="{{ old('area_atuacao', $osc->area_atuacao ?? '') }}">
                <p class="mt-2 text-xs text-slate-500" id="area-ajuda">Descreva a principal causa ou público atendido.</p>
                @error('area_atuacao')<p class="mt-2 text-sm font-medium text-red-700" id="area-erro">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section class="border-t border-slate-100 pt-8" aria-labelledby="contato-organizacao">
        <div class="mb-6">
            <p class="text-sm font-semibold text-blue-700">Etapa 2 de 2</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900" id="contato-organizacao">Contato e apresentação</h2>
            <p class="mt-1 text-sm leading-6 text-slate-500">Estes dados são opcionais, mas ajudam as pessoas a conhecer a OSC.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-slate-800" for="email">E-mail</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('email'), 'border-slate-300' => ! $errors->has('email')]) aria-describedby="@error('email') email-erro @enderror" aria-invalid="@error('email') true @else false @enderror" autocomplete="email" id="email" name="email" placeholder="contato@organizacao.org.br" type="email" value="{{ old('email', $osc->email ?? '') }}">
                @error('email')<p class="mt-2 text-sm font-medium text-red-700" id="email-erro">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800" for="telefone">Telefone</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('telefone'), 'border-slate-300' => ! $errors->has('telefone')]) aria-describedby="@error('telefone') telefone-erro @enderror" aria-invalid="@error('telefone') true @else false @enderror" id="telefone" inputmode="tel" name="telefone" placeholder="(00) 00000-0000" type="tel" value="{{ old('telefone', $osc->telefone ?? '') }}">
                @error('telefone')<p class="mt-2 text-sm font-medium text-red-700" id="telefone-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800" for="redes_sociais">Redes sociais</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('redes_sociais'), 'border-slate-300' => ! $errors->has('redes_sociais')]) aria-describedby="@error('redes_sociais') redes-erro @enderror" aria-invalid="@error('redes_sociais') true @else false @enderror" id="redes_sociais" name="redes_sociais" placeholder="Ex.: @suaosc ou instagram.com/suaosc" type="text" value="{{ old('redes_sociais', $osc->redes_sociais ?? '') }}">
                @error('redes_sociais')<p class="mt-2 text-sm font-medium text-red-700" id="redes-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800" for="descricao">Descrição</label>
                <textarea @class(['mt-2 block w-full resize-y rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100', 'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('descricao'), 'border-slate-300' => ! $errors->has('descricao')]) aria-describedby="descricao-ajuda @error('descricao') descricao-erro @enderror" aria-invalid="@error('descricao') true @else false @enderror" id="descricao" name="descricao" placeholder="Conte brevemente a missão, os projetos ou o impacto da organização." rows="5">{{ old('descricao', $osc->descricao ?? '') }}</textarea>
                <p class="mt-2 text-xs text-slate-500" id="descricao-ajuda">Uma apresentação curta facilita a descoberta da OSC.</p>
                @error('descricao')<p class="mt-2 text-sm font-medium text-red-700" id="descricao-erro">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>
</div>
