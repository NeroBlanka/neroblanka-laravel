<?php

namespace App\Enums;

enum DeliverableStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case REVISION_REQUESTED = 'revision_requested';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::SUBMITTED => 'Soumis',
            self::REVISION_REQUESTED => 'Révision demandée',
            self::APPROVED => 'Approuvé',
        };
    }
}
