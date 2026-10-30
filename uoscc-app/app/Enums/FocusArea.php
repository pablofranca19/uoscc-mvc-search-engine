<?php

namespace App\Enums;

enum FocusArea: string
{
    case EducationAndCitizenship = 'EducationAndCitizenship';
    case EnvironmentAndSustainability = 'EnvironmentAndSustainability';
    case HealthcareAndCommunityWellbeing = 'HealthcareAndCommunityWellbeing';
    case SocialAssistanceAndFamilySupport = 'SocialAssistanceAndFamilySupport';
    case HumanRightsAndSocialInclusion = 'HumanRightsAndSocialInclusion';
    case CultureSportsAndLeisure = 'CultureSportsAndLeisure';

    /**
     * Human-readable label (pt-BR) for the focus area.
     */
    public function label(): string
    {
        return match ($this) {
            self::EducationAndCitizenship => 'Educação e formação cidadã',
            self::EnvironmentAndSustainability => 'Meio ambiente e sustentabilidade',
            self::HealthcareAndCommunityWellbeing => 'Saúde e bem-estar comunitário',
            self::SocialAssistanceAndFamilySupport => 'Assistência social e apoio às famílias',
            self::HumanRightsAndSocialInclusion => 'Direitos humanos e inclusão social',
            self::CultureSportsAndLeisure => 'Cultura, esporte e lazer',
        };
    }
}
