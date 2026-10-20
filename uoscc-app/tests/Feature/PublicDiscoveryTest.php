<?php

use Illuminate\Support\Carbon;

test('activity discovery includes open registration status for filtering', function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));

    $this->get('/eventos')
        ->assertSee('Somente inscrições abertas')
        ->assertSee('data-registration="aberta"', false)
        ->assertSee('data-registration="encerrada"', false)
        ->assertSee('data-registration="esgotada"', false);
});

test('open registration metadata expires after the deadline', function () {
    $this->travelTo(Carbon::parse('2026-12-01 12:00:00', 'America/Bahia'));

    $this->get('/eventos')->assertDontSee('data-registration="aberta"', false);
});

test('space discovery exposes capacity structure region and a clear empty state', function () {
    $this->get('/espacos')
        ->assertSee('Capacidade mínima')
        ->assertSee('Estrutura necessária')
        ->assertSee('Região')
        ->assertSee('Nenhum espaço encontrado')
        ->assertSee('data-capacity="40"', false)
        ->assertSee('data-capacity="120"', false)
        ->assertSee('Wi-Fi')
        ->assertSee('Ar-condicionado');
});

test('supporter page requires a real account', function () {
    $this->get('/apoiadores')
        ->assertRedirect(route('login'));
});

test('public pages explain that consultation does not require an account', function (string $path) {
    $this->get($path)->assertSee('Explore eventos, projetos, organizações e espaços sem cadastro.')
        ->assertSee('Entrar ou criar conta de apoiador');
})->with(['/', '/eventos', '/espacos', '/organizacoes', '/eventos/1', '/espacos/1']);
