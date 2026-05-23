<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    private const ALLOWED_MIMES = ['application/pdf', 'image/png', 'image/jpeg'];
    private const MAX_BYTES = 10 * 1024 * 1024; // 10 MB

    public function uploadForLead(UploadedFile $file, Lead $lead): LeadFile
    {
        $this->assertAllowed($file);

        $path = Storage::disk('s3')->putFile("leads/{$lead->id}", $file);

        return LeadFile::create([
            'lead_id' => $lead->id,
            'disk' => 's3',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);
    }

    public function temporaryUrl(string $path, int $minutes = 30): string
    {
        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));
    }

    private function assertAllowed(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \InvalidArgumentException('Type de fichier non autorisé. Formats acceptés : PDF, PNG, JPEG.');
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw new \InvalidArgumentException('Fichier trop volumineux. Maximum 10 Mo.');
        }
    }
}
