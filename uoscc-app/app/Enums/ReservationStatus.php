<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    /**
     * Human-readable label (pt-BR) for the reservation status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Approved => 'Aprovada',
            self::Rejected => 'Recusada',
        };
    }
}
