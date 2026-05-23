<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LeadFile extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['lead_id', 'disk', 'path', 'original_name', 'mime_type', 'size_bytes'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($m) => $m->id = Str::uuid());
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function temporaryUrl(int $minutes = 30): string
    {
        return Storage::disk($this->disk)->temporaryUrl($this->path, now()->addMinutes($minutes));
    }
}
