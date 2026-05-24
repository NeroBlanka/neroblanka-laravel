<?php

namespace App\Models;

use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioItem extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'slug',
        'client_name',
        'service_type',
        'excerpt',
        'content',
        'cover_image',
        'gallery',
        'tags',
        'featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'service_type' => ServiceType::class,
            'gallery' => 'array',
            'tags' => 'array',
            'featured' => 'boolean',
            'published_at' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', today());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }
}
