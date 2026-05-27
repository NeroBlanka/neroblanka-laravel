<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Deliverable extends Model
{
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

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
            return 'approved';
        }
        if ($this->revision_notes !== null) {
            return 'revision';
        }
        return 'submitted';
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
