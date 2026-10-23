<?php

namespace App\Models;

use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'activity_id',
        'user_id',
        'participant_name',
        'participant_contact',
        'registered_at',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * The authenticated supporter that registered, when the registration was
     * made from the supporter dashboard instead of the public form.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Whether the supporter's participation was confirmed, which is what makes
     * the certificate available.
     */
    public function isParticipationConfirmed(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Human-readable (pt-BR) status shown on the supporter dashboard.
     */
    public function statusLabel(): string
    {
        return $this->isParticipationConfirmed()
            ? 'Participação confirmada'
            : 'Inscrição confirmada';
    }
}
