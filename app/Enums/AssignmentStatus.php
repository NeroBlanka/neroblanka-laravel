<?php

namespace App\Enums;

enum AssignmentStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case SUBMITTED = 'submitted';
    case REVISION_REQUESTED = 'revision_requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
            self::SUBMITTED => 'Livrable soumis',
            self::REVISION_REQUESTED => 'Révision demandée',
            self::APPROVED => 'Approuvé',
            self::CANCELLED => 'Annulé',
        };
    }
}
