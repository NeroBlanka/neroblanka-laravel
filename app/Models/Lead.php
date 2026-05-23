<?php

namespace App\Models;

use App\Enums\LeadStatus;
use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Lead extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company',
        'service_type',
        'status',
        'score',
        'budget_range',
        'deadline_range',
        'client_type',
        'source',
        'utm',
        'raw_payload',
        'social_links',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'service_type' => ServiceType::class,
            'utm' => 'array',
            'raw_payload' => 'array',
            'social_links' => 'array',
            'score' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
    }

    public function brief(): HasOne
    {
        return $this->hasOne(Brief::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(LeadFile::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(LeadEvent::class)->orderBy('created_at');
    }

    public function isHot(): bool
    {
        return $this->score >= 80;
    }

    public function isWarm(): bool
    {
        return $this->score >= 50 && $this->score < 80;
    }
}
