<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an organization manager sees only their organization profile', function () {
    $organization = Organization::factory()->create(['name' => 'Instituto Novos Caminhos']);
    $otherOrganization = Organization::factory()->create(['name' => 'Outra Organização']);
    $manager = User::factory()->organizationManager()->create(['organization_id' => $organization->id]);

    $this->actingAs($manager)->get(route('organizacoes.perfil'))
        ->assertOk()
        ->assertSee('Painel da OSC')
        ->assertSee('Instituto Novos Caminhos')
        ->assertSee('Publicar evento ou projeto')
        ->assertDontSee('Outra Organização');
});

test('a supporter cannot access the organization profile', function () {
    $supporter = User::factory()->supporter()->create();

    $this->actingAs($supporter)->get(route('organizacoes.perfil'))
        ->assertForbidden();
});

test('an organization manager can register a space with a new location', function () {
    $organization = Organization::factory()->create();
    $manager = User::factory()->organizationManager()->create(['organization_id' => $organization->id]);

    $this->actingAs($manager)->get(route('espacos.create'))
        ->assertOk()
        ->assertSee('Cadastrar espaço')
        ->assertSee('Endereço do espaço');

    $this->actingAs($manager)->post(route('espacos.store'), [
        'name' => 'Sala de Oficinas',
        'description' => 'Espaço para encontros e oficinas da comunidade.',
        'capacity' => 30,
        'facilities' => ['Wi-Fi', 'Projetor'],
        'cep' => '42800-000',
        'neighbourhood' => 'Centro',
        'street' => 'Rua das Flores',
        'number' => 120,
    ])->assertRedirectToRoute('organizacoes.perfil')
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('locations', [
        'cep' => '42800-000',
        'neighbourhood' => 'Centro',
        'street' => 'Rua das Flores',
        'number' => 120,
    ]);
    $this->assertDatabaseHas('spaces', [
        'name' => 'Sala de Oficinas',
        'organization_id' => $organization->id,
        'capacity' => 30,
    ]);
});

test('a supporter cannot access the space registration form', function () {
    $supporter = User::factory()->supporter()->create();

    $this->actingAs($supporter)->get(route('espacos.create'))
        ->assertForbidden();
});
