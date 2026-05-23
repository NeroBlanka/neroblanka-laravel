<?php

namespace App\Models;

use App\Scopes\FreelanceOwnedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Assignment extends Model
{
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'freelance_id',
        'assigned_at',
        'status',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
        static::addGlobalScope(new FreelanceOwnedScope());
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return static::withoutGlobalScopes()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function freelance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelance_id');
    }

    public function deliverables(): HasMany
    {
        return $this->hasMany(Deliverable::class);
    }
}
