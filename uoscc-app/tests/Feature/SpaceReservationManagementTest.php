<?php

use App\Enums\ReservationStatus;
use App\Models\Space;
use App\Models\SpaceReservation;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));
});

/**
 * Space 1 ("Espaço Conecta") belongs to organization 2 ("Conecta Comunidade")
 * in the demo catalog.
 */
function spaceManager(): User
{
    return User::factory()->organizationManager()->create(['organization_id' => 2]);
}

function pendingRequest(string $date = '2026-11-10'): SpaceReservation
{
    return SpaceReservation::factory()->create([
        'space_id' => 1,
        'requester_name' => 'Joana Pereira',
        'requester_contact' => 'joana@example.com',
        'requested_date' => $date,
        'purpose' => 'Roda de conversa sobre inclusão digital.',
    ]);
}

test('the responsible organization sees the reservation requests of its space', function () {
    pendingRequest();

    $this->actingAs(spaceManager())->get(route('espacos.reservas', 1))
        ->assertOk()
        ->assertSee('Espaço Conecta')
        ->assertSee('Joana Pereira')
        ->assertSee('joana@example.com')
        ->assertSee('Roda de conversa sobre inclusão digital.')
        ->assertSee('Pendente')
        ->assertSee('Aprovar reserva');
});

test('the reservation panel is closed to everyone but the responsible organization', function () {
    pendingRequest();

    $this->get(route('espacos.reservas', 1))->assertRedirect(route('login'));

    $this->actingAs(User::factory()->supporter()->create())
        ->get(route('espacos.reservas', 1))
        ->assertForbidden();

    $this->actingAs(User::factory()->organizationManager()->create(['organization_id' => 1]))
        ->get(route('espacos.reservas', 1))
        ->assertForbidden();
});

test('a manager approves a pending request', function () {
    $reservation = pendingRequest();

    $this->actingAs(spaceManager())
        ->post(route('espacos.reservas.aprovar', [1, $reservation->id]))
        ->assertRedirect(route('espacos.reservas', 1))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    expect($reservation->refresh()->status)->toBe(ReservationStatus::Approved);
    expect(Space::query()->findOrFail(1)->isAvailableOn('2026-11-10'))->toBeFalse();
});

test('a manager rejects a pending request and keeps the date available', function () {
    $reservation = pendingRequest();

    $this->actingAs(spaceManager())
        ->post(route('espacos.reservas.recusar', [1, $reservation->id]))
        ->assertRedirect(route('espacos.reservas', 1))
        ->assertSessionHas('success');

    expect($reservation->refresh()->status)->toBe(ReservationStatus::Rejected);
    expect(Space::query()->findOrFail(1)->isAvailableOn('2026-11-10'))->toBeTrue();
});

test('a second request for an approved date cannot be approved', function () {
    SpaceReservation::factory()->approved()->create([
        'space_id' => 1,
        'requested_date' => '2026-11-10',
    ]);
    $reservation = pendingRequest();

    $this->actingAs(spaceManager())
        ->post(route('espacos.reservas.aprovar', [1, $reservation->id]))
        ->assertRedirect(route('espacos.reservas', 1))
        ->assertSessionHasErrors('reserva')
        ->assertSessionMissing('success');

    expect($reservation->refresh()->status)->toBe(ReservationStatus::Pending);
});

test('a request that was already analysed cannot be decided again', function (string $route) {
    $reservation = SpaceReservation::factory()->approved()->create(['space_id' => 1]);

    $this->actingAs(spaceManager())
        ->post(route($route, [1, $reservation->id]))
        ->assertSessionHasErrors('reserva')
        ->assertSessionMissing('success');

    expect($reservation->refresh()->status)->toBe(ReservationStatus::Approved);
})->with(['espacos.reservas.aprovar', 'espacos.reservas.recusar']);

test('deciding a reservation of another space returns 404', function () {
    $reservation = SpaceReservation::factory()->create(['space_id' => 2]);

    $this->actingAs(spaceManager())
        ->post(route('espacos.reservas.aprovar', [1, $reservation->id]))
        ->assertNotFound();
});

test('the public form refuses a date that already has an approved reservation', function () {
    SpaceReservation::factory()->approved()->create([
        'space_id' => 1,
        'requested_date' => '2026-11-10',
    ]);

    $this->actingAs(User::factory()->supporter()->create())
        ->from(route('espacos.show', 1))->post(route('espacos.reserva', 1), [
            'nome' => 'Pessoa de Teste',
            'email' => 'teste@example.com',
            'data' => '2026-11-10',
            'finalidade' => 'Oficina de leitura para a comunidade.',
        ])
        ->assertRedirect(route('espacos.show', 1))
        ->assertSessionHasErrors('data')
        ->assertSessionMissing('success');

    $this->assertDatabaseMissing('space_reservations', ['requester_contact' => 'teste@example.com']);
});

test('the osc panel links to the reservation requests of each space', function () {
    pendingRequest();

    $this->actingAs(spaceManager())->get(route('organizacoes.perfil'))
        ->assertOk()
        ->assertSee('Solicitações de reserva')
        ->assertSee('1 aguardando análise')
        ->assertSee(route('espacos.reservas', 1), false);
});
