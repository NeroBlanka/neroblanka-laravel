<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'draft';
    case ASSIGNED = 'assigned';
    case ACTIVE = 'active';
    case SUBMITTED = 'submitted';
    case WAITING_CLIENT = 'waiting_client';
    case REVISION = 'revision';
    case REVISION_REQUESTED = 'revision_requested';
    case APPROVED = 'approved';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::ASSIGNED => 'Assigné',
            self::ACTIVE => 'En production',
            self::SUBMITTED => 'Livrable soumis',
            self::WAITING_CLIENT => 'En attente client',
            self::REVISION => 'Révision demandée',
            self::REVISION_REQUESTED => 'Révision demandée',
            self::APPROVED => 'Approuvé',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }
}
