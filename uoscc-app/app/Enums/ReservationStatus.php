<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
