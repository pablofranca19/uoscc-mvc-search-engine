<?php

use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));
});

test('public activity pages show open closed and full registration states', function (int $id, string $label, bool $open) {
    $response = $this->get('/eventos/'.$id);

    $response->assertSee($label);
    if ($open) {
        $response->assertSee('Confirmar inscrição');
    } else {
        $response->assertDontSee('Confirmar inscrição');
    }
})->with([
    'open' => [1, 'Inscrições abertas', true],
    'deadline passed' => [3, 'Inscrições encerradas', false],
    'full' => [4, 'Vagas esgotadas', false],
]);

test('a valid registration is persisted and confirmed', function () {
    $this->actingAs(User::factory()->supporter()->create());

    $response = $this->post('/eventos/1/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'teste@example.com']);

    $response->assertRedirectToRoute('eventos.show', 1)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Inscrição confirmada! Sua participação foi registrada.')
        ->assertSessionMissing('demo');

    $this->assertDatabaseHas('registrations', [
        'activity_id' => 1,
        'participant_name' => 'Pessoa de Teste',
        'participant_contact' => 'teste@example.com',
    ]);
});

test('a duplicate registration for the same activity is rejected', function () {
    $this->actingAs(User::factory()->supporter()->create());

    $this->post('/eventos/1/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'teste@example.com']);

    $response = $this->post('/eventos/1/inscricao', ['nome' => 'Outro Nome', 'email' => 'teste@example.com']);

    $response->assertRedirectToRoute('eventos.show', 1)
        ->assertSessionHasErrors('inscricao')
        ->assertSessionMissing('success');

    expect(Registration::query()->where('activity_id', 1)->where('participant_contact', 'teste@example.com')->count())->toBe(1);
});

test('invalid registration data returns errors and preserves input', function () {
    $response = $this->from('/eventos/1')->post('/eventos/1/inscricao', ['nome' => 'Ab', 'email' => 'invalido']);

    $response->assertRedirect('/eventos/1')
        ->assertSessionHasErrors(['nome', 'email'])
        ->assertSessionHasInput('nome', 'Ab')
        ->assertSessionMissing('demo');
});

test('an unavailable activity rejects a direct registration post', function (int $id) {
    $this->actingAs(User::factory()->supporter()->create());

    $response = $this->post('/eventos/'.$id.'/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'teste@example.com']);

    $response->assertRedirectToRoute('eventos.show', $id)
        ->assertSessionHasErrors('inscricao')
        ->assertSessionMissing('success');
})->with([3, 4]);

test('registration remains open through the final second of the deadline in Bahia', function () {
    $this->actingAs(User::factory()->supporter()->create());

    $this->travelTo(Carbon::parse('2026-10-23 23:59:59', 'America/Bahia'));

    $this->post('/eventos/1/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'teste@example.com'])
        ->assertSessionHas('success')->assertSessionHasNoErrors();
});

test('registration closes at midnight after the deadline in Bahia', function () {
    $this->actingAs(User::factory()->supporter()->create());

    $this->travelTo(Carbon::parse('2026-10-24 00:00:00', 'America/Bahia'));

    $this->post('/eventos/1/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'teste@example.com'])
        ->assertSessionHasErrors('inscricao')->assertSessionMissing('success');
});

test('a guest without an account is sent to sign up and returns to the activity afterwards', function () {
    $this->post('/eventos/1/inscricao', ['nome' => 'Pessoa de Teste', 'email' => 'nova@example.com'])
        ->assertRedirectToRoute('apoiadores.cadastro')
        ->assertSessionHasInput('name', 'Pessoa de Teste')
        ->assertSessionHasInput('email', 'nova@example.com')
        ->assertSessionHas('url.intended', route('eventos.show', 1));

    $this->assertDatabaseMissing('registrations', ['participant_contact' => 'nova@example.com']);

    $this->post(route('apoiadores.cadastro.store'), [
        'name' => 'Pessoa de Teste',
        'email' => 'nova@example.com',
        'password' => 'senha-segura',
        'password_confirmation' => 'senha-segura',
    ])->assertRedirect(route('eventos.show', 1));
});

test('a guest with an existing account is sent to sign in and returns to the activity afterwards', function () {
    $user = User::factory()->supporter()->create(['email' => 'maria@example.com']);

    $this->post('/eventos/1/inscricao', ['nome' => 'Maria da Silva', 'email' => 'maria@example.com'])
        ->assertRedirectToRoute('login')
        ->assertSessionHasInput('email', 'maria@example.com')
        ->assertSessionHas('url.intended', route('eventos.show', 1));

    $this->assertDatabaseMissing('registrations', ['participant_contact' => 'maria@example.com']);

    $this->post(route('apoiadores.login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('eventos.show', 1));
});

test('project details identify the content as a project', function () {
    $this->get('/eventos/5')->assertSee('Sobre o projeto')->assertSee('Início do projeto');
});

test('a valid reservation is persisted as pending for today or a future day', function (string $date) {
    $user = User::factory()->supporter()->create();

    $response = $this->actingAs($user)->post('/espacos/1/reserva', [
        'nome' => 'Pessoa de Teste', 'email' => 'teste@example.com',
        'data' => $date, 'finalidade' => 'Oficina de leitura para a comunidade.',
    ]);

    $response->assertRedirectToRoute('apoiadores.reservas')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Solicitação enviada! A organização responsável vai analisar a disponibilidade e entrar em contato.')
        ->assertSessionMissing('demo');

    $this->assertDatabaseHas('space_reservations', [
        'space_id' => 1,
        'user_id' => $user->id,
        'requester_name' => 'Pessoa de Teste',
        'requester_contact' => 'teste@example.com',
        'requested_date' => $date.' 00:00:00',
        'purpose' => 'Oficina de leitura para a comunidade.',
        'status' => 'Pending',
    ]);
})->with(['today' => '2026-10-15', 'future' => '2026-11-20']);

test('reservation rejects past impossible or missing dates', function (?string $date) {
    $response = $this->from('/espacos/1')->post('/espacos/1/reserva', [
        'nome' => 'Pessoa de Teste', 'email' => 'teste@example.com',
        'data' => $date, 'finalidade' => 'Oficina de leitura para a comunidade.',
    ]);

    $response->assertSessionHasErrors('data')->assertSessionMissing('success');
})->with(['past' => '2026-10-14', 'impossible' => '2026-02-30', 'missing' => null]);

test('reservation validates contact and purpose before persisting', function () {
    $response = $this->from('/espacos/1')->post('/espacos/1/reserva', [
        'nome' => '', 'email' => 'invalido', 'data' => '2026-10-16', 'finalidade' => 'curto',
    ]);

    $response->assertSessionHasErrors(['nome', 'email', 'finalidade'])->assertSessionMissing('success');
});

test('unknown activities and spaces return 404 on get and post', function (string $url, string $method) {
    $this->{$method}($url)->assertNotFound();
})->with([
    ['/eventos/999', 'get'], ['/eventos/999/inscricao', 'post'],
    ['/espacos/999', 'get'], ['/espacos/999/reserva', 'post'],
]);

test('validation errors render next to fields and escape old input', function () {
    $response = $this->followingRedirects()->from('/eventos/1')->post('/eventos/1/inscricao', [
        'nome' => '<script>alert(1)</script>',
        'email' => 'invalido',
    ]);

    $response->assertSee('Informe um e-mail válido.')
        ->assertSee('registration-email-error')
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false);
});
