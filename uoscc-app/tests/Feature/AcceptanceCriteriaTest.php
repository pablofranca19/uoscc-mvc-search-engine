<?php

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Space;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Criterion 1: An OSC registers an event/project informing date, location,
 * description and (if any) the number of spots.
 */
test('an osc registers an event with date, location, description and spots', function () {
    $organization = Organization::factory()->create();
    $space = Space::factory()->create([
        'organization_id' => $organization->id,
    ]);

    $activity = Activity::factory()->create([
        'organization_id' => $organization->id,
        'space_id' => $space->id,
        'title' => 'Oficina de Reciclagem',
        'description' => 'Oficina aberta sobre reciclagem.',
        'activity_date' => now()->addWeek()->toDateString(),
        'activity_type' => ActivityType::Event,
        'capacity' => 30,
    ]);

    expect($activity->organization->is($organization))->toBeTrue();
    expect($activity->activity_date)->not->toBeNull();
    expect($activity->space->location)->not->toBeNull();
    expect($activity->description)->not->toBeEmpty();
    expect($activity->capacity)->toBe(30);
    expect($activity->remainingSpots())->toBe(30);
});

/**
 * Criterion 1 variant: spots are optional (unlimited registrations).
 */
test('an activity may be registered without a capacity (unlimited)', function () {
    $activity = Activity::factory()->unlimited()->create();

    expect($activity->capacity)->toBeNull();
    expect($activity->remainingSpots())->toBeNull();
});

/**
 * Criterion 2: The event appears in the public area, visible to any visitor
 * without requiring login.
 */
test('activities are listable publicly without authentication', function () {
    $publicActivities = Activity::public()->get();

    expect(auth()->check())->toBeFalse();
    expect($publicActivities)->not->toBeEmpty();
    expect($publicActivities->pluck('title'))->toContain('Feira de Cidadania e Serviços');
});

/**
 * Criterion 3: A visitor filters by activity type or date and finds the event.
 */
test('a visitor can filter activities by type and by date', function () {
    $showcase = Activity::query()->where('title', 'Feira de Cidadania e Serviços')->firstOrFail();

    $byType = Activity::ofType(ActivityType::Event)->get();
    expect($byType->contains('id', $showcase->id))->toBeTrue();

    $byDate = Activity::onDate($showcase->activity_date->toDateString())->get();
    expect($byDate->contains('id', $showcase->id))->toBeTrue();

    $byBoth = Activity::ofType(ActivityType::Event)
        ->onDate($showcase->activity_date->toDateString())
        ->get();
    expect($byBoth->contains('id', $showcase->id))->toBeTrue();
});

/**
 * The seeder builds the four UOSCC demo organizations.
 */
test('the seeder creates the four uoscc organizations', function () {
    expect(Organization::count())->toBe(4);
    expect(Organization::pluck('name'))->toContain(
        'Instituto Viver Melhor',
        'Conecta Comunidade',
        'Rede Verde Camaçari',
        'Movimento Jovem UOSCC',
    );
});
