<?php

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
    $this->assertDatabaseHas('users', ['name' => 'Maria da Silva', 'email' => 'maria@example.com']);
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
