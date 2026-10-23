<?php

use App\Enums\UserRole;
use App\Models\User;

test('a user reads their own account page', function () {
    $user = User::factory()->supporter()->create(['name' => 'Maria da Silva']);

    $this->actingAs($user)->get(route('users.show', $user->id))
        ->assertOk()
        ->assertSee('Maria da Silva')
        ->assertSee($user->email)
        ->assertSee('Apoiador');
});

test('a manager reads the accounts of their own organization', function () {
    $manager = User::factory()->organizationManager()->create(['organization_id' => 1]);
    $colleague = User::factory()->supporter()->create(['organization_id' => 1]);

    $this->actingAs($manager)->get(route('users.show', $colleague->id))
        ->assertOk()
        ->assertSee($colleague->name)
        ->assertSee('Instituto Viver Melhor');
});

test('accounts of other people and other organizations are not readable', function () {
    $other = User::factory()->supporter()->create(['organization_id' => 1]);

    $this->get(route('users.show', $other->id))->assertRedirect(route('login'));

    $this->actingAs(User::factory()->supporter()->create())
        ->get(route('users.show', $other->id))
        ->assertForbidden();

    $this->actingAs(User::factory()->organizationManager()->create(['organization_id' => 2]))
        ->get(route('users.show', $other->id))
        ->assertForbidden();
});

test('a manager creates an account bound to their own organization', function () {
    $manager = User::factory()->organizationManager()->create(['organization_id' => 2]);

    $response = $this->actingAs($manager)->post(route('users.store'), [
        'name' => 'Nova Pessoa',
        'email' => 'nova@example.com',
        'password' => 'senha-secreta',
        'password_confirmation' => 'senha-secreta',
        'role' => UserRole::Supporter->value,
    ]);

    $created = User::query()->where('email', 'nova@example.com')->firstOrFail();

    $response->assertRedirectToRoute('users.show', $created->id)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'email' => 'nova@example.com',
        'organization_id' => 2,
        'role' => UserRole::Supporter->value,
    ]);

    $this->actingAs($manager)->get(route('users.show', $created->id))->assertOk();
});

test('only organization managers may create accounts', function () {
    $payload = [
        'name' => 'Nova Pessoa',
        'email' => 'nova@example.com',
        'password' => 'senha-secreta',
        'password_confirmation' => 'senha-secreta',
        'role' => UserRole::OrganizationManager->value,
    ];

    $this->post(route('users.store'), $payload)->assertRedirect(route('login'));

    $this->actingAs(User::factory()->supporter()->create())
        ->post(route('users.store'), $payload)
        ->assertForbidden();

    $this->actingAs(User::factory()->create())
        ->post(route('users.store'), $payload)
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'nova@example.com']);
});

test('creating an account validates every storable field', function () {
    $this->actingAs(User::factory()->organizationManager()->create(['organization_id' => 1]))
        ->from(route('organizacoes.perfil'))
        ->post(route('users.store'), [
            'name' => '',
            'email' => 'invalido',
            'password' => 'curta',
            'role' => UserRole::Visitor->value,
        ])
        ->assertSessionHasErrors(['name', 'email', 'password', 'role']);
});
