<?php

use App\ActivityRegistration;
use App\Http\Controllers\OscController;
use App\Http\Controllers\EventoController;
use App\Models\Evento;
use App\Http\Controllers\SupporterAuthController;
use App\Http\Controllers\SupporterDashboardController;
use App\Models\Osc;
use App\Http\Controllers\ActivityController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

$eventos = [
    1 => ['id' => 1, 'titulo' => 'Feira de Cidadania e Serviços', 'organizacao' => 'Instituto Viver Melhor', 'data' => '2026-10-24', 'hora' => '09:00', 'local' => 'Praça da Esperança', 'regiao' => 'Centro', 'tipo' => 'Ação comunitária', 'vagas' => 120, 'inscritos' => 78, 'descricao' => 'Um dia de serviços, orientação e atividades para aproximar a comunidade das organizações da sociedade civil.'],
    2 => ['id' => 2, 'titulo' => 'Oficina de Inclusão Digital', 'organizacao' => 'Conecta Comunidade', 'data' => '2026-10-29', 'hora' => '14:00', 'local' => 'Espaço Conecta', 'regiao' => 'Orla', 'tipo' => 'Oficina', 'vagas' => 30, 'inscritos' => 18, 'descricao' => 'Oficina prática para pessoas que querem desenvolver habilidades digitais para estudo, trabalho e serviços públicos.'],
    3 => ['id' => 3, 'titulo' => 'Mutirão Verde', 'organizacao' => 'Rede Verde Camaçari', 'data' => '2026-11-07', 'hora' => '08:00', 'local' => 'Parque Municipal', 'regiao' => 'Parque Verde', 'tipo' => 'Meio ambiente', 'vagas' => 80, 'inscritos' => 42, 'descricao' => 'Ação coletiva de educação ambiental, limpeza e plantio de mudas em área pública.'],
    4 => ['id' => 4, 'titulo' => 'Encontro de Juventudes', 'organizacao' => 'Movimento Jovem UOSCC', 'data' => '2026-11-14', 'hora' => '15:00', 'local' => 'Casa da Juventude', 'regiao' => 'Gleba A', 'tipo' => 'Juventude', 'vagas' => 60, 'inscritos' => 51, 'descricao' => 'Roda de conversa, cultura e construção de propostas para ampliar a participação da juventude na comunidade.'],
];

$eventos[5] = ['id' => 5, 'natureza' => 'projeto', 'titulo' => 'Trilhas Digitais — projeto demonstrativo', 'organizacao' => 'Conecta Comunidade', 'data' => '2026-11-07', 'hora' => '14:00', 'local' => 'Espaço Conecta', 'regiao' => 'Orla', 'tipo' => 'Oficina', 'vagas' => 25, 'inscritos' => 0, 'descricao' => 'Exemplo fictício para testar a descoberta de projetos: encontros de inclusão digital com início na data indicada.'];

$eventos[3]['prazo_inscricao'] = '2026-10-14';
$eventos[4]['inscritos'] = $eventos[4]['vagas'];

foreach ($eventos as &$evento) {
    $evento['prazo_inscricao'] ??= Carbon::parse($evento['data'])->subDay()->toDateString();
}
unset($evento);

$organizacoes = [
    1 => ['id' => 1, 'nome' => 'Instituto Viver Melhor', 'sigla' => 'IVM', 'area' => 'Assistência social', 'regiao' => 'Centro', 'descricao' => 'Atua com ações de cidadania, apoio comunitário e fortalecimento de famílias.', 'email' => 'contato@ivm.org.br', 'telefone' => '(71) 3000-1001'],
    2 => ['id' => 2, 'nome' => 'Conecta Comunidade', 'sigla' => 'CC', 'area' => 'Educação e tecnologia', 'regiao' => 'Orla', 'descricao' => 'Promove inclusão digital, formação profissional e acesso à tecnologia.', 'email' => 'contato@conecta.org.br', 'telefone' => '(71) 3000-1002'],
    3 => ['id' => 3, 'nome' => 'Rede Verde Camaçari', 'sigla' => 'RVC', 'area' => 'Meio ambiente', 'regiao' => 'Parque Verde', 'descricao' => 'Desenvolve projetos de educação ambiental, sustentabilidade e preservação.', 'email' => 'contato@redeverde.org.br', 'telefone' => '(71) 3000-1003'],
    4 => ['id' => 4, 'nome' => 'Movimento Jovem UOSCC', 'sigla' => 'MJU', 'area' => 'Juventude e cultura', 'regiao' => 'Gleba A', 'descricao' => 'Incentiva protagonismo juvenil, cultura e participação cidadã.', 'email' => 'contato@movimentojovem.org.br', 'telefone' => '(71) 3000-1004'],
];

foreach ($organizacoes as &$organizacao) {
    $organizacao['demonstrativa'] = true;
    $organizacao['redes_sociais'] = [];
}
unset($organizacao);

$organizationFromOsc = static function (Osc $osc): array {
    $initials = collect(preg_split('/\s+/', $osc->nome))
        ->filter()
        ->map(static fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
        ->take(3)
        ->implode('');

    return [
        'id' => 'osc-'.$osc->id,
        'nome' => $osc->nome,
        'sigla' => $initials,
        'area' => $osc->area_atuacao,
        'regiao' => $osc->outra_localizacao ?: ($osc->localizacao ?: 'Região não informada'),
        'descricao' => $osc->descricao ?: 'Esta organização ainda não informou uma apresentação.',
        'email' => $osc->email,
        'telefone' => $osc->telefone,
        'redes_sociais' => [],
        'demonstrativa' => false,
        'url' => route('organizacoes.cadastradas.show', $osc),
    ];
};

$catalogOrganizations = static function () use ($organizacoes, $organizationFromOsc): array {
    return [
        ...$organizacoes,
        ...Osc::query()->orderBy('nome')->get()->map($organizationFromOsc)->all(),
    ];
};

$espacos = [
    1 => ['id' => 1, 'nome' => 'Espaço Conecta', 'organizacao' => 'Conecta Comunidade', 'capacidade' => 40, 'regiao' => 'Orla', 'estrutura' => ['Wi-Fi', 'Projetor', 'Ar-condicionado', 'Mesas e cadeiras'], 'descricao' => 'Sala multiuso para cursos, reuniões, oficinas e pequenas apresentações.'],
    2 => ['id' => 2, 'nome' => 'Salão Viver Melhor', 'organizacao' => 'Instituto Viver Melhor', 'capacidade' => 120, 'regiao' => 'Centro', 'estrutura' => ['Palco', 'Som', 'Projetor', 'Banheiros'], 'descricao' => 'Salão amplo para eventos comunitários, palestras e atividades coletivas.'],
    3 => ['id' => 3, 'nome' => 'Casa Verde', 'organizacao' => 'Rede Verde Camaçari', 'capacidade' => 50, 'regiao' => 'Parque Verde', 'estrutura' => ['Área externa', 'Copa', 'Wi-Fi', 'Mesas'], 'descricao' => 'Espaço integrado à área verde, ideal para encontros, oficinas e atividades ambientais.'],
];

Route::get('/', function () use ($catalogOrganizations, $eventos, $espacos) {
    return view('home', [
        'eventos' => $eventos,
        'organizacoes' => $catalogOrganizations(),
        'espacos' => $espacos,
    ]);
})->name('home');

Route::get('/eventos', function () use ($eventos) {
    $filtrados = array_merge($eventos, Evento::query()->get()->map(fn (Evento $evento) => ['id' => 'cadastro-'.$evento->id, 'titulo' => $evento->titulo, 'organizacao' => $evento->organizacao, 'data' => $evento->data->toDateString(), 'hora' => $evento->hora, 'local' => $evento->local, 'regiao' => $evento->regiao, 'tipo' => $evento->tipo, 'vagas' => $evento->vagas, 'inscritos' => 0, 'descricao' => $evento->descricao, 'prazo_inscricao' => $evento->data->toDateString()])->all());

    return view('eventos.index', ['eventos' => $filtrados]);
})->name('eventos');
Route::resource('gestao/eventos', EventoController::class)->only(['index','create','store'])->names('gestao-eventos');

Route::middleware('guest')->group(function (): void {
    Route::get('/apoiadores/cadastro', [SupporterAuthController::class, 'create'])->name('apoiadores.cadastro');
    Route::post('/apoiadores/cadastro', [SupporterAuthController::class, 'store'])->name('apoiadores.cadastro.store');
    Route::get('/apoiadores/entrar', [SupporterAuthController::class, 'login'])->name('login');
    Route::post('/apoiadores/entrar', [SupporterAuthController::class, 'authenticate'])->name('apoiadores.login');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/apoiadores', [SupporterDashboardController::class, 'index'])->name('apoiadores');
    Route::put('/apoiadores/perfil', [SupporterDashboardController::class, 'updateProfile'])->name('apoiadores.perfil');
    Route::post('/apoiadores/favoritos/{type}/{id}', [SupporterDashboardController::class, 'toggleFavorite'])->name('apoiadores.favoritos');
    Route::post('/apoiadores/inscricoes/{type}/{id}', [SupporterDashboardController::class, 'register'])->name('apoiadores.inscricoes');
    Route::post('/apoiadores/participacoes/{type}/{id}/confirmar', [SupporterDashboardController::class, 'confirmParticipation'])->name('apoiadores.participacoes.confirmar');
    Route::get('/apoiadores/certificados/{type}/{id}', [SupporterDashboardController::class, 'certificate'])->name('apoiadores.certificados');
    Route::post('/apoiadores/sair', [SupporterAuthController::class, 'destroy'])->name('apoiadores.sair');
});

Route::get('/eventos/{id}', function (int $id) use ($eventos) {
    abort_unless(isset($eventos[$id]), 404);

    return view('eventos.show', ['evento' => $eventos[$id]]);
})->name('eventos.show');

Route::post('/eventos/{id}/inscricao', function (Request $request, int $id) use ($eventos): RedirectResponse {
    abort_unless(isset($eventos[$id]), 404);

    if (ActivityRegistration::status($eventos[$id]) !== 'aberta') {
        return redirect()->route('eventos.show', $id)->withErrors(['inscricao' => 'As inscrições desta atividade estão encerradas ou as vagas estão esgotadas.']);
    }

    $request->validate([
        'nome' => ['required', 'string', 'min:3', 'max:120'],
        'email' => ['required', 'email', 'max:254'],
    ], [
        'nome.required' => 'Informe seu nome.',
        'nome.string' => 'Informe um nome válido.',
        'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        'nome.max' => 'O nome deve ter no máximo 120 caracteres.',
        'email.required' => 'Informe seu e-mail.',
        'email.email' => 'Informe um e-mail válido.',
        'email.max' => 'O e-mail deve ter no máximo 254 caracteres.',
    ]);

    return redirect()->route('eventos.show', $id)->with('demo', 'Simulação concluída: os campos foram validados. Nenhuma inscrição foi registrada e nenhuma vaga foi reservada.');
})->name('eventos.inscricao');

Route::get('/organizacoes', function () use ($catalogOrganizations) {
    return view('organizacoes.index', [
        'organizacoes' => $catalogOrganizations(),
    ]);
})->name('organizacoes');

Route::get('/organizacoes/cadastradas/{osc}', function (Osc $osc) use ($organizationFromOsc) {
    return view('organizacoes.show', [
        'organizacao' => $organizationFromOsc($osc),
        'eventosOrganizacao' => [],
        'espacosOrganizacao' => [],
    ]);
})->name('organizacoes.cadastradas.show');

Route::get('/organizacoes/{id}', function (int $id) use ($organizacoes, $eventos, $espacos) {
    abort_unless(isset($organizacoes[$id]), 404);
    $organizacao = $organizacoes[$id];
    $eventosOrganizacao = collect($eventos)->where('organizacao', $organizacao['nome'])->values()->all();
    $espacosOrganizacao = collect($espacos)->where('organizacao', $organizacao['nome'])->values()->all();

    return view('organizacoes.show', compact('organizacao', 'eventosOrganizacao', 'espacosOrganizacao'));
})->name('organizacoes.show');

Route::get('/espacos', function () use ($espacos) {
    return view('espacos.index', [
        'espacos' => $espacos,
    ]);
})->name('espacos');

Route::get('/espacos/{id}', function (int $id) use ($espacos) {
    abort_unless(isset($espacos[$id]), 404);

    return view('espacos.show', ['espaco' => $espacos[$id]]);
})->name('espacos.show');

Route::post('/espacos/{id}/reserva', function (Request $request, int $id) use ($espacos): RedirectResponse {
    abort_unless(isset($espacos[$id]), 404);

    $request->validate([
        'nome' => ['required', 'string', 'min:3', 'max:120'],
        'email' => ['required', 'email', 'max:254'],
        'data' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.Carbon::now('America/Bahia')->toDateString()],
        'finalidade' => ['required', 'string', 'min:10', 'max:1000'],
    ], [
        'nome.required' => 'Informe seu nome.',
        'nome.string' => 'Informe um nome válido.',
        'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        'nome.max' => 'O nome deve ter no máximo 120 caracteres.',
        'email.required' => 'Informe seu e-mail.',
        'email.email' => 'Informe um e-mail válido.',
        'email.max' => 'O e-mail deve ter no máximo 254 caracteres.',
        'data.required' => 'Escolha a data desejada.',
        'data.date_format' => 'Informe uma data válida.',
        'data.after_or_equal' => 'Escolha hoje ou uma data futura.',
        'finalidade.required' => 'Descreva a finalidade da atividade.',
        'finalidade.string' => 'Informe uma descrição válida.',
        'finalidade.min' => 'Descreva a finalidade com pelo menos 10 caracteres.',
        'finalidade.max' => 'Use no máximo 1000 caracteres para a finalidade.',
    ]);

    return redirect()->route('espacos.show', $id)->with('demo', 'Simulação concluída: os campos foram validados. Nenhum pedido foi salvo ou enviado à OSC; a disponibilidade ainda não foi consultada.');
})->name('espacos.reserva');

Route::resource('oscs', OscController::class)->except('show');
Route::resource('atividades', ActivityController::class)->except('show');
