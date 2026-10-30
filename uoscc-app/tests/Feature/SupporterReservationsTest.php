<?php

use App\Models\SpaceReservation;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));
});

test('a guest without an account is sent to sign up to request a reservation', function () {
    $this->post(route('espacos.reserva', 1), [
        'nome' => 'Pessoa de Teste', 'email' => 'nova@example.com',
        'data' => '2026-11-20', 'finalidade' => 'Oficina de leitura para a comunidade.',
    ])
        ->assertRedirectToRoute('apoiadores.cadastro')
        ->assertSessionHasInput('name', 'Pessoa de Teste')
        ->assertSessionHasInput('email', 'nova@example.com')
        ->assertSessionHas('url.intended', route('espacos.show', 1));

    $this->assertDatabaseMissing('space_reservations', ['requester_contact' => 'nova@example.com']);
});

test('a guest with an account is sent to sign in to request a reservation', function () {
    User::factory()->supporter()->create(['email' => 'maria@example.com']);

    $this->post(route('espacos.reserva', 1), [
        'nome' => 'Maria da Silva', 'email' => 'maria@example.com',
        'data' => '2026-11-20', 'finalidade' => 'Oficina de leitura para a comunidade.',
    ])
        ->assertRedirectToRoute('login')
        ->assertSessionHasInput('email', 'maria@example.com')
        ->assertSessionHas('url.intended', route('espacos.show', 1));

    $this->assertDatabaseMissing('space_reservations', ['requester_contact' => 'maria@example.com']);
});

test('the reservation page redirects guests to login', function () {
    $this->get(route('apoiadores.reservas'))->assertRedirect(route('login'));
});

test('a supporter follows the status of their own reservation requests', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('espacos.reserva', 1), [
        'nome' => 'Maria da Silva', 'email' => 'maria@example.com',
        'data' => '2026-11-20', 'finalidade' => 'Oficina de leitura para a comunidade.',
    ])->assertRedirect(route('apoiadores.reservas'));

    $this->actingAs($user)->get(route('apoiadores.reservas'))
        ->assertOk()
        ->assertSee('Minhas reservas de espaços')
        ->assertSee('Espaço Conecta')
        ->assertSee('20/11/2026')
        ->assertSee('Pendente')
        ->assertSee('Oficina de leitura para a comunidade.');

    SpaceReservation::query()->where('user_id', $user->id)->firstOrFail()->approveRequest();

    $this->actingAs($user)->get(route('apoiadores.reservas'))
        ->assertSee('Aprovada');
});

test('a supporter does not see reservation requests made by someone else', function () {
    SpaceReservation::factory()->create([
        'space_id' => 1,
        'user_id' => User::factory()->supporter()->create()->id,
        'purpose' => 'Finalidade privada de outra pessoa.',
    ]);

    $this->actingAs(User::factory()->supporter()->create())->get(route('apoiadores.reservas'))
        ->assertOk()
        ->assertDontSee('Finalidade privada de outra pessoa.')
        ->assertSee('Você ainda não fez nenhuma reserva de espaço.');
});

test('the supporter dashboard links to the reservation page', function () {
    $this->actingAs(User::factory()->supporter()->create())->get(route('apoiadores'))
        ->assertOk()
        ->assertSee(route('apoiadores.reservas'), false);
});
