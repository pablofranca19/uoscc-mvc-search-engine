<?php

namespace App\Models;

use App\Enums\ActivityType;
use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    /** @use HasFactory<ActivityFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'space_id',
        'organization_id',
        'activity_type',
        'capacity',
        'registration_deadline',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'registration_deadline' => 'date',
            'activity_type' => ActivityType::class,
            'capacity' => 'integer',
        ];
    }

    /**
     * Owning organization (single responsible OSC).
     *
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Organizations collaborating in this activity.
     *
     * @return BelongsToMany<Organization, $this>
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }

    /**
     * @return BelongsTo<Space, $this>
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * @return HasMany<Registration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Whether registration is still open: a deadline exists, it has not
     * passed, and there are remaining spots (or unlimited capacity).
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_deadline === null) {
            return false;
        }

        if ($this->registration_deadline->isPast()) {
            return false;
        }

        $remaining = $this->remainingSpots();

        return $remaining === null || $remaining > 0;
    }

    /**
     * Remaining spots. Null means unlimited (no capacity defined).
     */
    public function remainingSpots(): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        return max(0, $this->capacity - $this->registrations()->count());
    }

    /**
     * Scope: activities visible on the public area.
     *
     * @param  Builder<Activity>  $query
     */
    public function scopePublic(Builder $query): void
    {
        // All registered activities are public; kept as a scope so the public
        // listing has a single, explicit source of truth it can filter on.
        $query->orderBy('activity_date');
    }

    /**
     * Scope: filter by activity type.
     *
     * @param  Builder<Activity>  $query
     */
    public function scopeOfType(Builder $query, ActivityType|string $type): void
    {
        $query->where('activity_type', $type instanceof ActivityType ? $type->value : $type);
    }

    /**
     * Scope: filter by a specific date.
     *
     * @param  Builder<Activity>  $query
     */
    public function scopeOnDate(Builder $query, string $date): void
    {
        $query->whereDate('activity_date', $date);
    }
}
