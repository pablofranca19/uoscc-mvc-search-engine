<?php

namespace App\Enums;

enum UserRole: string
{
    case Visitor = 'Visitor';
    case Supporter = 'Supporter';
    case OrganizationManager = 'OrganizationManager';

    /**
     * Human-readable label (pt-BR) for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Visitor => 'Visitante',
            self::Supporter => 'Apoiador',
            self::OrganizationManager => 'Responsável por OSC',
        };
    }
}
