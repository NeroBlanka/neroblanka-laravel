<?php

namespace App\Enums;

enum LeadStatus: string
{
    case NEW = 'new';
    case IN_REVIEW = 'in_review';
    case QUALIFIED = 'qualified';
    case NO_FIT = 'no_fit';
    case PROPOSAL_PENDING = 'proposal_pending';
    case PROPOSAL_SENT = 'proposal_sent';
    case WON = 'won';
    case LOST = 'lost';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Nouveau',
            self::IN_REVIEW => 'En cours d\'analyse',
            self::QUALIFIED => 'Qualifié',
            self::NO_FIT => 'Non compatible',
            self::PROPOSAL_PENDING => 'Proposition en cours',
            self::PROPOSAL_SENT => 'Proposition envoyée',
            self::WON => 'Gagné',
            self::LOST => 'Perdu',
            self::ARCHIVED => 'Archivé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::IN_REVIEW => 'yellow',
            self::QUALIFIED => 'green',
            self::NO_FIT => 'red',
            self::PROPOSAL_PENDING => 'orange',
            self::PROPOSAL_SENT => 'purple',
            self::WON => 'emerald',
            self::LOST => 'gray',
            self::ARCHIVED => 'gray',
        };
    }
}
