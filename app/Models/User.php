<?php

namespace App\Models;

use App\Scopes\FreelanceOwnedScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'company',
        'specialties',
        'is_available',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'specialties' => 'array',
            'is_available' => 'boolean',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'freelance_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isFreelance(): bool
    {
        return $this->role === 'freelance';
    }

    public function freelanceProfile(): HasOne
    {
        return $this->hasOne(FreelanceProfile::class, 'user_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'freelance_skills', 'freelance_id', 'skill_id');
    }
}
