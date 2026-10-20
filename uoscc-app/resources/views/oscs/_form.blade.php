<div class="space-y-10">
    <section aria-labelledby="dados-organizacao">
        <div class="mb-6">
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">Etapa 1 de 2</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900 dark:text-white" id="dados-organizacao">Sobre a organização</h2>
            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Informe como a OSC será apresentada na plataforma.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="nome">Nome da OSC <span class="text-red-600 dark:text-red-400">*</span></label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('nome'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('nome')]) aria-describedby="nome-ajuda @error('nome') nome-erro @enderror" aria-invalid="@error('nome') true @else false @enderror" id="nome" name="nome" placeholder="Ex.: Instituto Caminhos Abertos" required type="text" value="{{ old('nome', $osc->nome ?? '') }}">
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400" id="nome-ajuda">Use o nome pelo qual a organização é conhecida.</p>
                @error('nome')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="nome-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="area_atuacao">Área de atuação <span class="text-red-600 dark:text-red-400">*</span></label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('area_atuacao'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('area_atuacao')]) aria-describedby="area-ajuda @error('area_atuacao') area-erro @enderror" aria-invalid="@error('area_atuacao') true @else false @enderror" id="area_atuacao" name="area_atuacao" placeholder="Ex.: Educação e cidadania" required type="text" value="{{ old('area_atuacao', $osc->area_atuacao ?? '') }}">
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400" id="area-ajuda">Descreva a principal causa ou público atendido.</p>
                @error('area_atuacao')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="area-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="localizacao">Localização</label>
                <select class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-950" data-other-location id="localizacao" name="localizacao">
                    <option value="">Selecione a região de atuação</option>
                    @foreach(['Camaçari — Centro', 'Camaçari — Orla', 'Camaçari — Gleba A', 'Camaçari — Parque Verde', 'Outra região'] as $localizacao)
                        <option value="{{ $localizacao }}" @selected(old('localizacao', $osc->localizacao ?? '') === $localizacao)>{{ $localizacao }}</option>
                    @endforeach
                </select>
                @error('localizacao')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2" data-other-location-field hidden>
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="outra_localizacao">Qual é a região?</label>
                <input class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-950" id="outra_localizacao" name="outra_localizacao" placeholder="Ex.: Camaçari — Barra do Pojuca" type="text" value="{{ old('outra_localizacao', $osc->outra_localizacao ?? '') }}">
                @error('outra_localizacao')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section class="border-t border-slate-100 pt-8 dark:border-slate-800" aria-labelledby="contato-organizacao">
        <div class="mb-6">
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">Etapa 2 de 2</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900 dark:text-white" id="contato-organizacao">Contato e apresentação</h2>
            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Estes dados são opcionais, mas ajudam as pessoas a conhecer a OSC.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="email">E-mail</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('email'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('email')]) aria-describedby="@error('email') email-erro @enderror" aria-invalid="@error('email') true @else false @enderror" autocomplete="email" id="email" name="email" placeholder="contato@organizacao.org.br" type="email" value="{{ old('email', $osc->email ?? '') }}">
                @error('email')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="email-erro">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="telefone">Telefone</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('telefone'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('telefone')]) aria-describedby="telefone-ajuda @error('telefone') telefone-erro @enderror" aria-invalid="@error('telefone') true @else false @enderror" autocomplete="tel-national" data-phone-mask id="telefone" inputmode="tel" maxlength="15" name="telefone" placeholder="(00) 00000-0000" type="tel" value="{{ old('telefone', $osc->telefone ?? '') }}">
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400" id="telefone-ajuda">Informe o DDD antes do número de telefone.</p>
                @error('telefone')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="telefone-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="redes_sociais">Redes sociais</label>
                <input @class(['mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('redes_sociais'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('redes_sociais')]) aria-describedby="@error('redes_sociais') redes-erro @enderror" aria-invalid="@error('redes_sociais') true @else false @enderror" id="redes_sociais" name="redes_sociais" placeholder="Ex.: @suaosc ou instagram.com/suaosc" type="text" value="{{ old('redes_sociais', $osc->redes_sociais ?? '') }}">
                @error('redes_sociais')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="redes-erro">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200" for="descricao">Descrição</label>
                <textarea @class(['mt-2 block w-full resize-y rounded-xl border bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-950', 'border-red-400 focus:border-red-500 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-950' => $errors->has('descricao'), 'border-slate-300 dark:border-slate-700' => ! $errors->has('descricao')]) aria-describedby="descricao-ajuda @error('descricao') descricao-erro @enderror" aria-invalid="@error('descricao') true @else false @enderror" id="descricao" name="descricao" placeholder="Conte brevemente a missão, os projetos ou o impacto da organização." rows="5">{{ old('descricao', $osc->descricao ?? '') }}</textarea>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400" id="descricao-ajuda">Uma apresentação curta facilita a descoberta da OSC.</p>
                @error('descricao')<p class="mt-2 text-sm font-medium text-red-700 dark:text-red-300" id="descricao-erro">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>
</div>
