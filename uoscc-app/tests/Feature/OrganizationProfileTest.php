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
