<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FunnelEvent extends Model
{
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lead_id',
        'event',
        'session_id',
        'metadata',
        'utm',
        'ip',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'utm' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($m) {
            $m->id = (string) Str::uuid();
            $m->created_at ??= now();
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
