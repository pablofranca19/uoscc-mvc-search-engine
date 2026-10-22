<?php

use App\Models\SpaceReservation;

test('the reservations page lists every reservation of a space with its status', function () {
    SpaceReservation::factory()->create([
        'space_id' => 1,
        'requester_name' => 'Maria Solicitante',
        'requester_contact' => 'maria@example.com',
        'requested_date' => '2026-11-20',
        'purpose' => 'Oficina de leitura para a comunidade.',
    ]);
    SpaceReservation::factory()->approved()->create([
        'space_id' => 1,
        'requester_name' => 'João Aprovado',
        'requested_date' => '2026-11-25',
    ]);
    SpaceReservation::factory()->rejected()->create([
        'space_id' => 1,
        'requester_name' => 'Ana Recusada',
        'requested_date' => '2026-11-30',
    ]);

    $response = $this->get('/espacos/1/reservas');

    $response->assertOk()
        ->assertSee('Maria Solicitante')
        ->assertSee('João Aprovado')
        ->assertSee('Ana Recusada')
        ->assertSee('Pendente')
        ->assertSee('Aprovada')
        ->assertSee('Recusada')
        ->assertSee('Oficina de leitura para a comunidade.');
});

test('the reservations page shows an empty state when a space has no reservations', function () {
    $this->get('/espacos/2/reservas')
        ->assertOk()
        ->assertSee('Nenhuma reserva registrada');
});

test('the reservations page returns 404 for an unknown space', function () {
    $this->get('/espacos/999/reservas')->assertNotFound();
});
