<?php

namespace App\Enums;

enum NoFitReason: string
{
    case BUDGET_TOO_LOW = 'budget_too_low';
    case DEADLINE_UNREALISTIC = 'deadline_unrealistic';
    case NOT_PREMIUM_FIT = 'not_premium_fit';
    case UNCLEAR_REQUEST = 'unclear_request';
    case OUTSIDE_SCOPE = 'outside_scope';

    public function label(): string
    {
        return match($this) {
            self::BUDGET_TOO_LOW => 'Budget insuffisant',
            self::DEADLINE_UNREALISTIC => 'Délai irréaliste',
            self::NOT_PREMIUM_FIT => 'Pas un profil premium',
            self::UNCLEAR_REQUEST => 'Demande peu claire',
            self::OUTSIDE_SCOPE => 'Hors périmètre',
        };
    }
}
