<?php

namespace App\Models;

use App\Scopes\ClientOwnedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'service_type',
        'status',
        'budget_da',
        'deadline',
        'brief_file_url',
        'reference_urls',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
        static::addGlobalScope(new ClientOwnedScope());
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return static::withoutGlobalScopes()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
