<?php

use App\Models\Activity;
use App\Models\Organization;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->travelTo(Carbon::parse('2026-10-15 12:00:00', 'America/Bahia'));
});

test('displays the supporter dashboard with the catalog read from the database', function () {
    $this->actingAs(User::factory()->supporter()->create())->get(route('apoiadores'))
        ->assertOk()
        ->assertSee('Painel pessoal')
        ->assertSee('Inscrição simplificada')
        ->assertSee(Activity::query()->findOrFail(1)->title)
        ->assertSee(Organization::query()->findOrFail(1)->name);
});

test('omits closed and full activities from the simplified registration catalog', function () {
    $this->actingAs(User::factory()->supporter()->create())->get(route('apoiadores'))
        ->assertOk()
        ->assertSee('Oficina de Inclusão Digital')
        ->assertDontSee('Mutirão Verde')
        ->assertDontSee('Encontro de Juventudes');
});

test('saves a supporter profile used by simplified registration', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->put(route('apoiadores.perfil'), [
        'name' => 'Maria da Silva',
        'email' => 'maria@example.com',
        'skills' => 'Comunicação',
        'interests' => 'Educação',
        'availability' => 'Sábados',
    ])->assertRedirect(route('apoiadores'));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Maria da Silva',
        'email' => 'maria@example.com',
        'supporter_skills' => 'Comunicação',
        'supporter_interests' => 'Educação',
        'supporter_availability' => 'Sábados',
    ]);

    $this->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHasNoErrors()
        ->assertSessionMissing('supporter.registrations');

    $this->assertDatabaseHas('registrations', [
        'activity_id' => 1,
        'user_id' => $user->id,
        'participant_name' => 'Maria da Silva',
        'participant_contact' => 'maria@example.com',
        'completed_at' => null,
    ]);
});

test('saves favorites in the database and removes them on a second toggle', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('apoiadores.favoritos', ['osc', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionMissing('supporter.favorites');

    $this->assertDatabaseHas('supporter_favorites', [
        'user_id' => $user->id,
        'favoritable_type' => Organization::class,
        'favoritable_id' => 1,
    ]);

    $this->actingAs($user)->get(route('apoiadores'))
        ->assertOk()
        ->assertSee('Remover dos favoritos');

    $this->actingAs($user)->post(route('apoiadores.favoritos', ['osc', '1']))
        ->assertRedirect(route('apoiadores'));

    $this->assertDatabaseMissing('supporter_favorites', [
        'user_id' => $user->id,
        'favoritable_type' => Organization::class,
        'favoritable_id' => 1,
    ]);
});

test('saves an activity favorite for the authenticated supporter', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('apoiadores.favoritos', ['projeto', '5']))
        ->assertRedirect(route('apoiadores'));

    $this->assertDatabaseHas('supporter_favorites', [
        'user_id' => $user->id,
        'favoritable_type' => Activity::class,
        'favoritable_id' => 5,
    ]);
});

test('confirms participation and exposes the certificate from the database', function () {
    $user = User::factory()->supporter()->create(['name' => 'Maria da Silva']);

    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertRedirect(route('apoiadores'));

    $this->actingAs($user)->post(route('apoiadores.participacoes.confirmar', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('success', 'Participação registrada. Seu certificado já está disponível.');

    $registration = Registration::query()
        ->where('activity_id', 1)
        ->where('user_id', $user->id)
        ->firstOrFail();

    expect($registration->isParticipationConfirmed())->toBeTrue();
    expect($registration->completed_at->toDateString())->toBe('2026-10-15');

    $this->actingAs($user)->get(route('apoiadores.certificados', ['evento', '1']))
        ->assertOk()
        ->assertSee('Certificado de participação')
        ->assertSee('Maria da Silva')
        ->assertSee('Feira de Cidadania e Serviços');
});

test('withholds the certificate until participation is confirmed', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']));

    $this->actingAs($user)->get(route('apoiadores.certificados', ['evento', '1']))
        ->assertNotFound();
});

test('requires a completed profile before simplified registration', function () {
    $user = User::factory()->supporter()->create(['name' => '']);

    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHas('success', 'Complete seu perfil para usar a inscrição simplificada.');

    $this->assertDatabaseMissing('registrations', ['user_id' => $user->id]);
});

test('does not register the same supporter twice for one activity', function () {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']));
    $this->actingAs($user)->post(route('apoiadores.inscricoes', ['evento', '1']))
        ->assertSessionHas('success', 'Você já está inscrito nesta atividade.');

    expect(Registration::query()->where('activity_id', 1)->where('user_id', $user->id)->count())->toBe(1);
});

test('rejects simplified registration when the activity is closed or full', function (string $type, string $id) {
    $user = User::factory()->supporter()->create();

    $this->actingAs($user)->post(route('apoiadores.inscricoes', [$type, $id]))
        ->assertRedirect(route('apoiadores'))
        ->assertSessionHasErrors('inscricao');

    $this->assertDatabaseMissing('registrations', ['user_id' => $user->id]);
})->with([
    'deadline passed' => ['evento', '3'],
    'no spots left' => ['evento', '4'],
]);

test('confirming participation without a registration returns 404', function () {
    $this->actingAs(User::factory()->supporter()->create())
        ->post(route('apoiadores.participacoes.confirmar', ['evento', '1']))
        ->assertNotFound();
});

test('unknown favorites and registrations return 404', function (string $route, array $parameters) {
    $this->actingAs(User::factory()->supporter()->create())
        ->post(route($route, $parameters))
        ->assertNotFound();
})->with([
    'unknown organization' => ['apoiadores.favoritos', ['osc', '999']],
    'unknown activity' => ['apoiadores.favoritos', ['evento', '999']],
    'wrong activity type' => ['apoiadores.favoritos', ['projeto', '1']],
    'unsupported type' => ['apoiadores.favoritos', ['espaco', '1']],
    'unknown registration target' => ['apoiadores.inscricoes', ['evento', '999']],
]);
