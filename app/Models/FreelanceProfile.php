<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FreelanceProfile extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'bio',
        'portfolio_url',
        'hourly_rate',
        'rating',
        'completed_count',
        'service_types',
    ];

    protected function casts(): array
    {
        return [
            'service_types' => 'array',
            'rating' => 'decimal:2',
            'completed_count' => 'integer',
            'hourly_rate' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
    }

    public function freelance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
