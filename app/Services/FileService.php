<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    // Listes complémentaires : docs (pdf), images (png/jpeg), archives (zip/rar),
    // vidéos (mp4/mov/webm/mkv). Pour fichiers > 400 Mo, l'utilisateur fournit un
    // lien de transfert externe (Swisstransfer / WeTransfer / Drive) côté wizard.
    private const ALLOWED_MIMES = [
        'application/pdf',
        'image/png',
        'image/jpeg',
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/vnd.rar',
        'application/octet-stream', // .rar / .zip mal détectés selon OS
        'video/mp4',
        'video/quicktime',
        'video/webm',
        'video/x-matroska',
    ];
    private const MAX_BYTES = 400 * 1024 * 1024; // 400 MB

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
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            throw new \InvalidArgumentException('Type de fichier non autorisé. Formats : PDF, PNG, JPEG, ZIP, RAR, MP4, MOV, WEBM, MKV.');
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw new \InvalidArgumentException('Fichier trop volumineux. Maximum 400 Mo — pour plus gros, utilisez un lien Swisstransfer/WeTransfer.');
        }
    }
}
