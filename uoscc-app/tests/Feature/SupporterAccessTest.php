<?php

use App\Models\SupporterApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login and registration pages render real protected post forms', function () {
    $this->get('/apoiadores/entrar')->assertOk()->assertSee('name="password"', false)->assertSee('method="POST"', false)->assertDontSee('Explorar painel de demonstração');
    $this->get('/apoiadores/cadastro')->assertOk()->assertSee('name="password_confirmation"', false)->assertSee('Criar conta e continuar');
});

test('authenticated visitors are sent to their access flow instead of a second signup', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/apoiadores/cadastro')->assertRedirect(route('apoiadores.acesso'));
    $this->get('/apoiadores/acesso')->assertRedirect(route('apoiadores.solicitacao'));
    $this->get('/apoiadores/solicitacao')->assertOk()->assertSee('Enviar solicitação');
});

function supporterSignupPayload(array $overrides = []): array
{
    return array_merge(['name' => 'Pessoa de teste', 'email' => 'conta@example.invalid', 'password' => 'Senha-local-12345', 'password_confirmation' => 'Senha-local-12345'], $overrides);
}

function supporterApplicationPayload(array $overrides = []): array
{
    return array_merge(['applicant_type' => 'person', 'interests' => 'Educação', 'contribution' => 'Quero colaborar com oficinas de tecnologia na comunidade.'], $overrides);
}

test('guests cannot open supporter or team pages', function (string $path) {
    $this->get($path)->assertRedirect(route('apoiadores.login'));
})->with(['/apoiadores/painel', '/apoiadores/perfil', '/apoiadores/oportunidades', '/apoiadores/solicitacao', '/apoiadores/equipe']);

test('registration stores hashed credentials and cannot grant privileges', function () {
    $response = $this->post('/apoiadores/cadastro', supporterSignupPayload(['email' => 'CONTA@example.invalid', 'is_supporter_admin' => true, 'role' => 'Supporter', 'status' => 'approved']));

    $response->assertRedirect(route('apoiadores.solicitacao'));
    $user = User::where('email', 'conta@example.invalid')->sole();
    expect(Hash::check('Senha-local-12345', $user->password))->toBeTrue();
    expect($user->is_supporter_admin)->toBeFalse();
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseCount('supporter_applications', 0);
});

test('registration rejects invalid identity and password fields', function (array $overrides, string $field) {
    $this->post('/apoiadores/cadastro', supporterSignupPayload($overrides))->assertSessionHasErrors($field);

    $this->assertGuest();
    $this->assertDatabaseCount('users', 0);
})->with([
    [['name' => 'A'], 'name'],
    [['email' => 'invalido'], 'email'],
    [['password' => 'curta', 'password_confirmation' => 'curta'], 'password'],
    [['password_confirmation' => 'outra-senha-123'], 'password'],
    [['password' => str_repeat('á', 40), 'password_confirmation' => str_repeat('á', 40)], 'password'],
]);

test('registration rejects an existing email after normalization', function () {
    User::factory()->create(['email' => 'conta@example.invalid']);

    $this->post('/apoiadores/cadastro', supporterSignupPayload(['email' => 'CONTA@example.invalid']))->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 1);
});

test('login accepts valid credentials and resumes the request flow', function () {
    $user = User::factory()->create(['email' => 'conta@example.invalid', 'password' => 'Senha-local-12345']);

    $this->post('/apoiadores/entrar', ['email' => 'CONTA@example.invalid', 'password' => 'Senha-local-12345'])->assertRedirect(route('apoiadores.acesso'));
    $this->assertAuthenticatedAs($user);
});

test('invalid login does not reveal whether the account exists or retain the password', function (bool $existing) {
    if ($existing) {
        User::factory()->create(['email' => 'conta@example.invalid']);
    }

    $this->post('/apoiadores/entrar', ['email' => 'conta@example.invalid', 'password' => 'senha-incorreta'])->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos.'])->assertSessionMissing('_old_input.password');
    $this->assertGuest();
})->with([true, false]);

test('login limits repeated attempts', function () {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/apoiadores/entrar', ['email' => 'limite@example.invalid', 'password' => 'incorreta']);
    }

    $this->post('/apoiadores/entrar', ['email' => 'limite@example.invalid', 'password' => 'incorreta'])->assertSessionHasErrors(['email' => 'Muitas tentativas. Aguarde um minuto antes de tentar novamente.']);
    $this->assertGuest();
});

test('login rejects passwords longer than the hashing limit instead of truncating them', function () {
    User::factory()->create(['email' => 'conta@example.invalid', 'password' => str_repeat('á', 36)]);

    $this->post('/apoiadores/entrar', ['email' => 'conta@example.invalid', 'password' => str_repeat('á', 36).'x'])->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('submission is owned by the current user and always starts pending', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($user)->post('/apoiadores/solicitacao', supporterApplicationPayload(['user_id' => $other->id, 'status' => 'approved', 'reviewed_by' => $other->id]))->assertRedirect(route('apoiadores.solicitacao'));
    $this->assertDatabaseHas('supporter_applications', ['user_id' => $user->id, 'status' => 'pending', 'reviewed_by' => null]);
    $this->assertDatabaseMissing('supporter_applications', ['user_id' => $other->id]);
});

test('organizations need a name and applications need a meaningful description', function (array $overrides, string $field) {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/apoiadores/solicitacao', supporterApplicationPayload($overrides))->assertSessionHasErrors($field);
    $this->assertDatabaseCount('supporter_applications', 0);
})->with([
    [['applicant_type' => 'organization'], 'organization_name'],
    [['applicant_type' => 'admin'], 'applicant_type'],
    [['interests' => ''], 'interests'],
    [['contribution' => 'ajudar'], 'contribution'],
]);

test('pending applicants cannot enter exclusive pages', function (string $path) {
    $application = SupporterApplication::factory()->create();

    $this->actingAs($application->user)->get($path)->assertRedirect(route('apoiadores.solicitacao'));
})->with(['/apoiadores/painel', '/apoiadores/perfil', '/apoiadores/oportunidades']);

test('approved supporters can enter their panel without gaining administrative powers', function () {
    $application = SupporterApplication::factory()->create(['status' => 'approved']);

    $this->actingAs($application->user)->get('/apoiadores/painel')->assertOk()->assertSee($application->user->name)->assertDontSee('Olá, Alex!');
    expect(Gate::forUser($application->user)->allows('review-supporters'))->toBeFalse();
});

test('non administrators cannot read or review other applications', function () {
    $application = SupporterApplication::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get('/apoiadores/equipe')->assertForbidden();
    $this->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'approved'])->assertForbidden();
    expect($application->fresh()->status)->toBe('pending');
});

test('administrators can approve and record the reviewer', function () {
    $application = SupporterApplication::factory()->create();
    $admin = User::factory()->create(['is_supporter_admin' => true]);

    $this->actingAs($admin)->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'approved'])->assertRedirect(route('apoiadores.admin'));
    $this->assertDatabaseHas('supporter_applications', ['id' => $application->id, 'status' => 'approved', 'reviewed_by' => $admin->id]);
    expect($application->fresh()->reviewed_at)->not->toBeNull();
});

test('administrators cannot approve their own applications', function () {
    $admin = User::factory()->create(['is_supporter_admin' => true]);
    $application = SupporterApplication::factory()->for($admin)->create();

    $this->actingAs($admin)->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'approved'])->assertForbidden();
    expect($application->fresh()->status)->toBe('pending');
});

test('requesting changes requires an explanation', function () {
    $application = SupporterApplication::factory()->create();
    $admin = User::factory()->create(['is_supporter_admin' => true]);

    $this->actingAs($admin)->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'changes_requested'])->assertSessionHasErrors('review_note');
    expect($application->fresh()->status)->toBe('pending');
});

test('reviewed applications cannot be overwritten with a stale decision', function () {
    $application = SupporterApplication::factory()->create(['status' => 'approved']);
    $admin = User::factory()->create(['is_supporter_admin' => true]);

    $this->actingAs($admin)->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'changes_requested', 'review_note' => 'Atualize os dados.'])->assertStatus(409);
    expect($application->fresh()->status)->toBe('approved');
});

test('applicants can resubmit requested changes but cannot alter a pending request', function (string $status, int $expectedStatus) {
    $application = SupporterApplication::factory()->create(['status' => $status, 'review_note' => 'Descreva melhor sua contribuição.']);

    $response = $this->actingAs($application->user)->post('/apoiadores/solicitacao', supporterApplicationPayload(['interests' => 'Cultura']));
    $response->assertStatus($expectedStatus);
    expect($application->fresh()->interests)->toBe($status === 'changes_requested' ? 'Cultura' : 'Educação e meio ambiente');
    expect($application->fresh()->status)->toBe($status === 'approved' ? 'approved' : 'pending');
    $this->assertDatabaseCount('supporter_applications', 1);
})->with([['changes_requested', 302], ['pending', 409], ['approved', 409]]);

test('an applicant can see only their own request and escaped review feedback', function () {
    $application = SupporterApplication::factory()->create(['status' => 'changes_requested', 'review_note' => '<script>alert(1)</script>']);
    SupporterApplication::factory()->create(['contribution' => 'conteudo privado de outra pessoa']);

    $this->actingAs($application->user)->get('/apoiadores/solicitacao')->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('conteudo privado de outra pessoa');
});

test('profile updates only the owner and cannot grant privileges or approval', function () {
    $application = SupporterApplication::factory()->create(['status' => 'approved']);
    $other = User::factory()->create(['name' => 'Outra pessoa']);

    $this->actingAs($application->user)->patch('/apoiadores/perfil', ['name' => 'Nome atualizado', 'interests' => 'Saúde', 'id' => $other->id, 'is_supporter_admin' => true, 'email' => 'outro@example.invalid'])->assertRedirect(route('apoiadores.perfil'));
    expect($application->user->fresh()->name)->toBe('Nome atualizado');
    expect($application->user->fresh()->is_supporter_admin)->toBeFalse();
    expect($other->fresh()->name)->toBe('Outra pessoa');
    expect($application->fresh()->interests)->toBe('Saúde');
});

test('team dashboard shows real submissions and forbids caching', function () {
    $application = SupporterApplication::factory()->create();
    $admin = User::factory()->create(['is_supporter_admin' => true]);

    $response = $this->actingAs($admin)->get('/apoiadores/equipe');
    $response->assertOk()->assertSee($application->user->name);
    expect($response->headers->get('Cache-Control'))->toContain('no-store');
});

test('approved profile and opportunities render without fabricated opportunities', function (string $path) {
    $application = SupporterApplication::factory()->create(['status' => 'approved']);

    $this->actingAs($application->user)->get($path)->assertOk()->assertDontSee('Exemplo fictício');
})->with(['/apoiadores/perfil', '/apoiadores/oportunidades']);

test('logout ends the session and blocks the panel again', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/apoiadores/sair')->assertRedirect(route('apoiadores.login'));
    $this->assertGuest();
    $this->get('/apoiadores/painel')->assertRedirect(route('apoiadores.login'));
});

test('local command grants and revokes review access for an existing account', function () {
    $user = User::factory()->create();

    $this->artisan('apoiadores:administrador', ['email' => $user->email])->assertExitCode(0);
    expect($user->fresh()->is_supporter_admin)->toBeTrue();
    $this->artisan('apoiadores:administrador', ['email' => $user->email, '--revogar' => true])->assertExitCode(0);
    expect($user->fresh()->is_supporter_admin)->toBeFalse();
});

test('administrative command does not create accounts for unknown emails', function () {
    $this->artisan('apoiadores:administrador', ['email' => 'inexistente@example.invalid'])->assertExitCode(1);

    $this->assertDatabaseCount('users', 0);
});

test('a registered applicant reaches the panel after an administrator approves and they sign in again', function () {
    $this->post('/apoiadores/cadastro', supporterSignupPayload())->assertRedirect(route('apoiadores.solicitacao'));
    $applicant = User::where('email', 'conta@example.invalid')->sole();
    $this->post('/apoiadores/solicitacao', supporterApplicationPayload())->assertRedirect(route('apoiadores.solicitacao'));
    $this->get('/apoiadores/painel')->assertRedirect(route('apoiadores.solicitacao'));
    $this->post('/apoiadores/sair');
    $admin = User::factory()->create(['is_supporter_admin' => true]);
    $application = $applicant->supporterApplication()->sole();
    $this->actingAs($admin)->patch('/apoiadores/equipe/'.$application->id, ['decision' => 'approved'])->assertRedirect(route('apoiadores.admin'));
    $this->post('/apoiadores/sair');

    $this->post('/apoiadores/entrar', ['email' => 'conta@example.invalid', 'password' => 'Senha-local-12345'])->assertRedirect(route('apoiadores.acesso'));
    $this->get('/apoiadores/acesso')->assertRedirect(route('apoiadores.painel'));
    $this->get('/apoiadores/painel')->assertOk()->assertSee('Pessoa de teste');
    $this->assertDatabaseHas('supporter_applications', ['user_id' => $applicant->id, 'status' => 'approved', 'reviewed_by' => $admin->id]);
    expect($applicant->fresh()->is_supporter_admin)->toBeFalse();
});
