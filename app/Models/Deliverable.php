<?php

namespace App\Models;

use App\Enums\DeliverableStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Deliverable extends Model
{
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $appends = ['status'];

    protected $fillable = [
        'assignment_id',
        'file_url',
        'file_name',
        'message',
        'submitted_at',
        'approved_at',
        'revision_notes',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function getStatusAttribute(): string
    {
        if ($this->approved_at !== null) {
            return DeliverableStatus::APPROVED->value;
        }
        if (filled($this->revision_notes)) {
            return DeliverableStatus::REVISION_REQUESTED->value;
        }
        if ($this->submitted_at !== null) {
            return DeliverableStatus::SUBMITTED->value;
        }
        return DeliverableStatus::DRAFT->value;
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
