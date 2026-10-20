<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Database\Factories\SpaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    /** @use HasFactory<SpaceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'facilities',
        'location_id',
        'organization_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'facilities' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return HasMany<SpaceReservation, $this>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(SpaceReservation::class);
    }

    /**
     * Determine whether the space has no approved reservation on the given date.
     */
    public function isAvailableOn(string $requestedDate): bool
    {
        return ! $this->reservations()
            ->where('requested_date', $requestedDate)
            ->where('status', ReservationStatus::Approved)
            ->exists();
    }
}
