<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Brief extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['lead_id', 'answers'];

    protected function casts(): array
    {
        return ['answers' => 'array'];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
