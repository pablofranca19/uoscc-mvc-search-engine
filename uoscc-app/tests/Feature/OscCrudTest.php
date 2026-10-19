<?php

use App\Models\Osc;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('lists registered oscs ordered by name', function () {
    $firstOsc = Osc::factory()->create(['nome' => 'Ação Comunitária']);
    $secondOsc = Osc::factory()->create(['nome' => 'Projeto Social']);

    $response = $this->get(route('oscs.index'));

    $response
        ->assertOk()
        ->assertSeeInOrder([$firstOsc->nome, $secondOsc->nome]);
});

test('creates an osc with valid data', function () {
    $data = [
        'nome' => 'Instituto Cidadania',
        'area_atuacao' => 'Educação',
        'email' => 'contato@instituto.test',
        'telefone' => '(11) 99999-9999',
        'redes_sociais' => '@institutocidadania',
        'descricao' => 'Atua em projetos educacionais.',
    ];

    $response = $this->post(route('oscs.store'), $data);

    $response
        ->assertRedirect(route('oscs.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('oscs', $data);
});

test('validates required fields when creating an osc', function () {
    $response = $this->post(route('oscs.store'), []);

    $response->assertSessionHasErrors(['nome', 'area_atuacao']);
});

test('displays a phone field configured for Brazilian phone formatting', function () {
    $response = $this->get(route('oscs.create'));

    $response
        ->assertOk()
        ->assertSee('data-phone-mask', false)
        ->assertSee('maxlength="15"', false);
});

test('updates an osc', function () {
    $osc = Osc::factory()->create();

    $response = $this->put(route('oscs.update', $osc), [
        'nome' => 'Nome atualizado',
        'area_atuacao' => 'Saúde',
        'email' => 'atualizado@osc.test',
        'telefone' => null,
        'redes_sociais' => null,
        'descricao' => null,
    ]);

    $response
        ->assertRedirect(route('oscs.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('oscs', [
        'id' => $osc->id,
        'nome' => 'Nome atualizado',
        'area_atuacao' => 'Saúde',
    ]);
});

test('deletes an osc', function () {
    $osc = Osc::factory()->create();

    $response = $this->delete(route('oscs.destroy', $osc));

    $response
        ->assertRedirect(route('oscs.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('oscs', ['id' => $osc->id]);
});
