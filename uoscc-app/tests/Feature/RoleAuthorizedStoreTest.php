<?php

use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;
use App\Models\User;

function visitor(): User
{
    return User::factory()->create();
}

function organizationManager(): User
{
    return User::factory()->organizationManager()->create(['organization_id' => 1]);
}

test('a visitor cannot create an organization', function () {
    $this->actingAs(visitor())->post('/organizacoes', [
        'name' => 'Nova Organização',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'focus_area' => 'EducationAndCitizenship',
    ])->assertForbidden();

    $this->assertDatabaseMissing('organizations', ['name' => 'Nova Organização']);
});

test('a guest cannot create an organization', function () {
    $this->post('/organizacoes', [
        'name' => 'Nova Organização',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'focus_area' => 'EducationAndCitizenship',
    ])->assertForbidden();
});

test('a supporter cannot create an organization', function () {
    $this->actingAs(User::factory()->supporter()->create())->post('/organizacoes', [
        'name' => 'Nova Organização',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'focus_area' => 'EducationAndCitizenship',
    ])->assertForbidden();
});

test('an organization manager can create an organization', function () {
    $user = organizationManager();

    $response = $this->actingAs($user)->post('/organizacoes', [
        'name' => 'Nova Organização',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'contact_email' => 'contato@example.com',
        'contact_phone' => '71999990000',
        'social_media_links' => 'https://instagram.com/nova',
        'focus_area' => 'EducationAndCitizenship',
    ]);

    $organization = Organization::query()->where('name', 'Nova Organização')->firstOrFail();

    $response->assertRedirectToRoute('organizacoes.show', $organization->id)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('organizations', [
        'name' => 'Nova Organização',
        'contact_email' => 'contato@example.com',
        'focus_area' => 'EducationAndCitizenship',
    ]);
});

test('creating an organization validates every storable field', function () {
    $this->actingAs(organizationManager())->from('/organizacoes')->post('/organizacoes', [
        'name' => 'ab',
        'description' => 'curto',
        'contact_email' => 'invalido',
        'focus_area' => 'NotAValidArea',
    ])->assertSessionHasErrors(['name', 'description', 'contact_email', 'focus_area']);
});

test('a visitor cannot create an activity', function () {
    $this->actingAs(visitor())->post('/eventos', [
        'title' => 'Nova Atividade',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'activity_date' => '2026-12-01',
        'activity_type' => 'Event',
    ])->assertForbidden();

    $this->assertDatabaseMissing('activities', ['title' => 'Nova Atividade']);
});

test('an organization manager can create an activity owned by their organization', function () {
    $response = $this->actingAs(organizationManager())->post('/eventos', [
        'title' => 'Nova Atividade',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'activity_date' => '01/12/2026',
        'space_id' => 1,
        'activity_type' => 'Event',
        'capacity' => 50,
        'registration_deadline' => '30/11/2026',
    ]);

    $activity = Activity::query()->where('title', 'Nova Atividade')->firstOrFail();

    $response->assertRedirectToRoute('eventos.show', $activity->id)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('activities', [
        'title' => 'Nova Atividade',
        'organization_id' => 1,
        'activity_type' => 'Event',
        'capacity' => 50,
    ]);
    expect($activity->activity_date->toDateString())->toBe('2026-12-01');
    expect($activity->registration_deadline?->toDateString())->toBe('2026-11-30');
});

test('creating an activity validates every storable field', function () {
    $this->actingAs(organizationManager())->from('/eventos')->post('/eventos', [
        'title' => 'ab',
        'description' => 'curto',
        'activity_date' => 'not-a-date',
        'space_id' => 999,
        'activity_type' => 'Invalid',
        'capacity' => 0,
        'registration_deadline' => '2027-01-01',
    ])->assertSessionHasErrors([
        'title',
        'description',
        'activity_date',
        'space_id',
        'activity_type',
        'capacity',
    ]);
});

test('a visitor cannot create a space', function () {
    $this->actingAs(visitor())->post('/espacos', [
        'name' => 'Novo Espaço',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'capacity' => 30,
        'location_id' => 1,
    ])->assertForbidden();

    $this->assertDatabaseMissing('spaces', ['name' => 'Novo Espaço']);
});

test('an organization manager can create a space owned by their organization', function () {
    $response = $this->actingAs(organizationManager())->post('/espacos', [
        'name' => 'Novo Espaço',
        'description' => 'Uma descrição suficientemente longa para passar.',
        'capacity' => 30,
        'facilities' => ['Wi-Fi', 'Projetor'],
        'location_id' => 1,
    ]);

    $space = Space::query()->where('name', 'Novo Espaço')->firstOrFail();

    $response->assertRedirectToRoute('espacos.show', $space->id)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('spaces', [
        'name' => 'Novo Espaço',
        'organization_id' => 1,
        'capacity' => 30,
    ]);

    expect($space->facilities)->toBe(['Wi-Fi', 'Projetor']);
});

test('creating a space validates every storable field', function () {
    $this->actingAs(organizationManager())->from('/espacos')->post('/espacos', [
        'name' => 'ab',
        'description' => 'curto',
        'capacity' => 0,
        'facilities' => 'not-a-list',
        'location_id' => 999,
    ])->assertSessionHasErrors([
        'name',
        'description',
        'capacity',
        'facilities',
        'location_id',
    ]);
});
