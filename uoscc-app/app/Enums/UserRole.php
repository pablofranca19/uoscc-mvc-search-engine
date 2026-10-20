<?php

namespace App\Enums;

enum UserRole: string
{
    case Visitor = 'Visitor';
    case Supporter = 'Supporter';
    case OrganizationManager = 'OrganizationManager';
}
