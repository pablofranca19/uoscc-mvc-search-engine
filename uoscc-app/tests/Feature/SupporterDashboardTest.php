<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('displays the supporter dashboard', function () {
    $this->actingAs(User::factory()->create())->get(route('apoiadores'))
        ->assertOk()
        ->assertSee('Painel pessoal')
        ->assertSee('Inscrição simplificada');
});

test('saves a supporter profile used by simplified registration', function () {
    $this->actingAs(User::factory()->create())->put(route('apoiadores.perfil'), [
        'name' => 'Maria da Silva',
        'email' => 'maria@example.com',
        'skills' => 'Comunicação',
        'interests' => 'Educação',
        'availability' => 'Sábados',
    ])->assertRedirect(route('apoiadores'));

    $this->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('supporter.registrations.evento:1.status', 'Inscrição confirmada');
});

test('saves favorites and exposes a certificate after participation is confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('apoiadores.favoritos', ['osc', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('supporter.favorites.osc:1.name', 'Instituto Viver Melhor');

    $this->actingAs($user)->withSession([
        'supporter.registrations' => [
            'evento:1' => ['id' => '1', 'type' => 'Evento', 'title' => 'Feira de Cidadania e Serviços', 'organization' => 'Instituto Viver Melhor', 'date' => '2026-10-24', 'area' => 'Cidadania', 'status' => 'Inscrição confirmada'],
        ],
    ])->post(route('apoiadores.participacoes.confirmar', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('supporter.registrations.evento:1.status', 'Participação confirmada');

    $user->update(['name' => 'Maria da Silva']);

    $this->actingAs($user)->withSession([
        'supporter.registrations' => [
            'evento:1' => ['id' => '1', 'type' => 'Evento', 'title' => 'Feira de Cidadania e Serviços', 'organization' => 'Instituto Viver Melhor', 'date' => '2026-10-24', 'area' => 'Cidadania', 'status' => 'Participação confirmada', 'completed_at' => '2026-10-24'],
        ],
    ])->get(route('apoiadores.certificados', ['evento', '1']))
        ->assertOk()
        ->assertSee('Certificado de participação')
        ->assertSee('Maria da Silva');
});

test('requires a completed profile before simplified registration', function () {
    $user = User::factory()->create(['name' => '']);

    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('success', 'Complete seu perfil para usar a inscrição simplificada.');
});
