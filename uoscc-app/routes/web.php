<?php

use Illuminate\Support\Facades\Route;

$eventos = [
    1 => ['id' => 1, 'titulo' => 'Feira de Cidadania e Serviços', 'organizacao' => 'Instituto Viver Melhor', 'data' => '2026-10-24', 'hora' => '09:00', 'local' => 'Praça da Esperança', 'regiao' => 'Centro', 'tipo' => 'Ação comunitária', 'vagas' => 120, 'inscritos' => 78, 'descricao' => 'Um dia de serviços, orientação e atividades para aproximar a comunidade das organizações da sociedade civil.'],
    2 => ['id' => 2, 'titulo' => 'Oficina de Inclusão Digital', 'organizacao' => 'Conecta Comunidade', 'data' => '2026-10-29', 'hora' => '14:00', 'local' => 'Espaço Conecta', 'regiao' => 'Orla', 'tipo' => 'Oficina', 'vagas' => 30, 'inscritos' => 18, 'descricao' => 'Oficina prática para pessoas que querem desenvolver habilidades digitais para estudo, trabalho e serviços públicos.'],
    3 => ['id' => 3, 'titulo' => 'Mutirão Verde', 'organizacao' => 'Rede Verde Camaçari', 'data' => '2026-11-07', 'hora' => '08:00', 'local' => 'Parque Municipal', 'regiao' => 'Parque Verde', 'tipo' => 'Meio ambiente', 'vagas' => 80, 'inscritos' => 42, 'descricao' => 'Ação coletiva de educação ambiental, limpeza e plantio de mudas em área pública.'],
    4 => ['id' => 4, 'titulo' => 'Encontro de Juventudes', 'organizacao' => 'Movimento Jovem UOSCC', 'data' => '2026-11-14', 'hora' => '15:00', 'local' => 'Casa da Juventude', 'regiao' => 'Gleba A', 'tipo' => 'Juventude', 'vagas' => 60, 'inscritos' => 51, 'descricao' => 'Roda de conversa, cultura e construção de propostas para ampliar a participação da juventude na comunidade.'],
];

$eventos[5] = ['id' => 5, 'natureza' => 'projeto', 'titulo' => 'Trilhas Digitais — projeto demonstrativo', 'organizacao' => 'Conecta Comunidade', 'data' => '2026-11-07', 'hora' => '14:00', 'local' => 'Espaço Conecta', 'regiao' => 'Orla', 'tipo' => 'Oficina', 'vagas' => 25, 'inscritos' => 0, 'descricao' => 'Exemplo fictício para testar a descoberta de projetos: encontros de inclusão digital com início na data indicada.'];

$organizacoes = [
    1 => ['id' => 1, 'nome' => 'Instituto Viver Melhor', 'sigla' => 'IVM', 'area' => 'Assistência social', 'regiao' => 'Centro', 'descricao' => 'Atua com ações de cidadania, apoio comunitário e fortalecimento de famílias.', 'email' => 'contato@ivm.org.br', 'telefone' => '(71) 3000-1001'],
    2 => ['id' => 2, 'nome' => 'Conecta Comunidade', 'sigla' => 'CC', 'area' => 'Educação e tecnologia', 'regiao' => 'Orla', 'descricao' => 'Promove inclusão digital, formação profissional e acesso à tecnologia.', 'email' => 'contato@conecta.org.br', 'telefone' => '(71) 3000-1002'],
    3 => ['id' => 3, 'nome' => 'Rede Verde Camaçari', 'sigla' => 'RVC', 'area' => 'Meio ambiente', 'regiao' => 'Parque Verde', 'descricao' => 'Desenvolve projetos de educação ambiental, sustentabilidade e preservação.', 'email' => 'contato@redeverde.org.br', 'telefone' => '(71) 3000-1003'],
    4 => ['id' => 4, 'nome' => 'Movimento Jovem UOSCC', 'sigla' => 'MJU', 'area' => 'Juventude e cultura', 'regiao' => 'Gleba A', 'descricao' => 'Incentiva protagonismo juvenil, cultura e participação cidadã.', 'email' => 'contato@movimentojovem.org.br', 'telefone' => '(71) 3000-1004'],
];

$espacos = [
    1 => ['id' => 1, 'nome' => 'Espaço Conecta', 'organizacao' => 'Conecta Comunidade', 'capacidade' => 40, 'regiao' => 'Orla', 'estrutura' => ['Wi-Fi', 'Projetor', 'Ar-condicionado', 'Mesas e cadeiras'], 'descricao' => 'Sala multiuso para cursos, reuniões, oficinas e pequenas apresentações.'],
    2 => ['id' => 2, 'nome' => 'Salão Viver Melhor', 'organizacao' => 'Instituto Viver Melhor', 'capacidade' => 120, 'regiao' => 'Centro', 'estrutura' => ['Palco', 'Som', 'Projetor', 'Banheiros'], 'descricao' => 'Salão amplo para eventos comunitários, palestras e atividades coletivas.'],
    3 => ['id' => 3, 'nome' => 'Casa Verde', 'organizacao' => 'Rede Verde Camaçari', 'capacidade' => 50, 'regiao' => 'Parque Verde', 'estrutura' => ['Área externa', 'Copa', 'Wi-Fi', 'Mesas'], 'descricao' => 'Espaço integrado à área verde, ideal para encontros, oficinas e atividades ambientais.'],
];

Route::get('/', function () use ($eventos, $organizacoes) {
    return view('home', [
        'eventos' => $eventos,
        'organizacoes' => $organizacoes,
    ]);
})->name('home');

Route::get('/eventos', function () use ($eventos) {
    $filtrados = $eventos;

    return view('eventos.index', ['eventos' => $filtrados]);
})->name('eventos');

Route::get('/eventos/{id}', function (int $id) use ($eventos) {
    abort_unless(isset($eventos[$id]), 404);

    return view('eventos.show', ['evento' => $eventos[$id]]);
})->name('eventos.show');

Route::post('/eventos/{id}/inscricao', function (int $id) use ($eventos) {
    abort_unless(isset($eventos[$id]), 404);

    return back()->with('success', 'Sua solicitação de inscrição foi recebida!');
})->name('eventos.inscricao');

Route::get('/organizacoes', function () use ($organizacoes) {
    return view('organizacoes.index', [
        'organizacoes' => $organizacoes,
    ]);
})->name('organizacoes');

Route::get('/organizacoes/{id}', function (int $id) use ($organizacoes, $eventos) {
    abort_unless(isset($organizacoes[$id]), 404);
    $organizacao = $organizacoes[$id];
    $eventosOrganizacao = collect($eventos)->where('organizacao', $organizacao['nome'])->values()->all();

    return view('organizacoes.show', compact('organizacao', 'eventosOrganizacao'));
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

Route::post('/espacos/{id}/reserva', function (int $id) use ($espacos) {
    abort_unless(isset($espacos[$id]), 404);

    return back()->with('success', 'Solicitação de reserva enviada com sucesso!');
})->name('espacos.reserva');
