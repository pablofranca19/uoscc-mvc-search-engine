<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Database\Factories\SpaceReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaceReservation extends Model
{
    /** @use HasFactory<SpaceReservationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'space_id',
        'user_id',
        'requester_name',
        'requester_contact',
        'requested_date',
        'purpose',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'status' => ReservationStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Space, $this>
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Approve the reservation request. Only meaningful while pending.
     */
    public function approveRequest(): void
    {
        $this->update(['status' => ReservationStatus::Approved]);
    }

    /**
     * Reject the reservation request. Only meaningful while pending.
     */
    public function rejectRequest(): void
    {
        $this->update(['status' => ReservationStatus::Rejected]);
    }
}
