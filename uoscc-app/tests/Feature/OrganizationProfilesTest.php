<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\ViewErrorBag;

test('the public directory preserves the four original organizations and discovery controls', function () {
    $response = $this->get('/organizacoes');

    $response->assertSee('perfis fictícios')
        ->assertSee('organization-search')
        ->assertSee('Área de atuação')
        ->assertSee('Região')
        ->assertViewHas('organizacoes', fn (array $organizations): bool => count($organizations) === 4)
        ->assertDontSee('Saúde em Comunidade')
        ->assertDontSee('Rede de Direitos e Inclusão');
});

test('each organization has a public profile without login', function (int $id) {
    $this->get('/organizacoes/'.$id)
        ->assertSee('Perfil demonstrativo')
        ->assertSee('Redes sociais')
        ->assertSee('Nenhuma rede social confirmada.');
})->with([1, 2, 3, 4]);

test('organization profile shows only its own activities and spaces', function () {
    $this->get('/organizacoes/2')
        ->assertSee('Oficina de Inclusão Digital')
        ->assertSee('Trilhas Digitais')
        ->assertSee('Espaço Conecta')
        ->assertDontSee('Feira de Cidadania e Serviços')
        ->assertDontSee('Salão Viver Melhor');
});

test('a profile without published activities or contacts explains the empty states', function () {
    $organization = $this->get('/organizacoes/4')->viewData('organizacao');
    $organization['email'] = null;
    $organization['telefone'] = null;

    $html = view('organizacoes.show', [
        'organizacao' => $organization,
        'eventosOrganizacao' => [],
        'espacosOrganizacao' => [],
        'errors' => new ViewErrorBag,
    ])->render();

    expect($html)->toContain('Esta organização ainda não tem atividades publicadas no protótipo.')
        ->toContain('Nenhum espaço publicado para esta organização.')
        ->toContain('Ainda não informado')
        ->toContain('Explorar atividades da rede');
});

test('unknown organization returns 404', function () {
    $this->get('/organizacoes/999')->assertNotFound();
});

test('home counters describe catalog contents instead of invented impact metrics', function () {
    $response = $this->get('/');

    $response->assertSee('Dados demonstrativos, calculados a partir do conteúdo disponível.')
        ->assertSee('projetos no catálogo')
        ->assertSee('espaços no catálogo')
        ->assertDontSee('24+')
        ->assertDontSee('eventos no mês');
});

test('home counters reflect an empty catalog', function () {
    $html = view('home', ['eventos' => [], 'organizacoes' => [], 'espacos' => [], 'errors' => new ViewErrorBag])->render();

    expect(substr_count($html, '<strong class="text-3xl font-black">0</strong>'))->toBe(4);
});

test('organization detail labels its project and displays the project deadline', function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));

    $this->get('/organizacoes/2')->assertSee('Projeto')->assertSee('06/11/2026');
});
