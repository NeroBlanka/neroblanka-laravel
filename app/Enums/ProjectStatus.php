<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case WAITING_CLIENT = 'waiting_client';
    case REVISION_REQUESTED = 'revision_requested';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::ACTIVE => 'En production',
            self::WAITING_CLIENT => 'En attente client',
            self::REVISION_REQUESTED => 'Révision demandée',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }
}
