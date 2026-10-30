<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a supporter can create an account and is authenticated', function () {
    $response = $this->post(route('apoiadores.cadastro.store'), [
        'name' => 'Maria da Silva',
        'email' => 'maria@example.com',
        'password' => 'senha-segura',
        'password_confirmation' => 'senha-segura',
    ]);

    $response->assertRedirect(route('apoiadores'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'name' => 'Maria da Silva',
        'email' => 'maria@example.com',
        'role' => UserRole::Supporter->value,
    ]);
});

test('an existing supporter can sign in and sign out', function () {
    $user = User::factory()->create(['email' => 'maria@example.com']);

    $this->post(route('apoiadores.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('apoiadores'));

    $this->assertAuthenticatedAs($user);

    $this->post(route('apoiadores.sair'))
        ->assertRedirect(route('apoiadores.login'));

    $this->assertGuest();
});

test('the supporter dashboard redirects guests to login', function () {
    $response = $this->get(route('apoiadores'));

    $response->assertRedirect(route('login'));
});

test('an organization can register itself with a responsible account', function () {
    $response = $this->post(route('organizacoes.cadastro.store'), [
        'name' => 'Instituto Novos Caminhos',
        'description' => 'Promove oportunidades para a comunidade local.',
        'focus_area' => 'EducationAndCitizenship',
        'manager_name' => 'Joana Santos',
        'manager_email' => 'joana@novoscaminhos.test',
        'password' => 'senha-segura',
        'password_confirmation' => 'senha-segura',
    ]);

    $user = User::query()->where('email', 'joana@novoscaminhos.test')->firstOrFail();

    $response->assertRedirect(route('organizacoes.perfil'));
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('organizations', ['name' => 'Instituto Novos Caminhos']);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'role' => UserRole::OrganizationManager->value,
        'organization_id' => $user->organization_id,
    ]);
});

test('only an organization manager can access the event form', function () {
    $supporter = User::factory()->supporter()->create();
    $manager = User::factory()->organizationManager()->create(['organization_id' => 1]);

    $this->actingAs($supporter)->get(route('eventos.create'))
        ->assertForbidden();

    $this->actingAs($manager)->get(route('eventos.create'))
        ->assertOk()
        ->assertSee('Cadastre um evento ou projeto')
        ->assertSee('placeholder="dd/mm/aaaa"', false)
        ->assertSee('Use o formato dia/mês/ano.');
});

test('authentication forms offer password visibility controls', function () {
    $this->get(route('apoiadores.cadastro'))
        ->assertOk()
        ->assertSee('data-password-toggle="register-password register-password-confirmation"', false)
        ->assertSee('aria-label="Mostrar senhas"', false);

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('data-password-toggle="login-password"', false)
        ->assertSee('aria-label="Mostrar senha"', false);
});
