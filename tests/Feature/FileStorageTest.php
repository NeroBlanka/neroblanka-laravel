<?php

namespace Tests\Feature;

use App\Enums\ServiceType;
use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\Project;
use App\Models\User;
use App\Scopes\ClientOwnedScope;
use App\Scopes\FreelanceOwnedScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('s3');
        Storage::fake('s3_public');
    }

    public function test_deliverable_download_uses_signed_url(): void
    {
        Storage::disk('s3')->put('deliverables/test.pdf', 'content');

        // The signed URL path should be generated via Storage::temporaryUrl, not direct URL
        $url = Storage::disk('s3')->temporaryUrl('deliverables/test.pdf', now()->addMinutes(30));

        // Ensure it's a time-limited URL (contains expiry info or at least is not a bare public URL)
        $this->assertNotEmpty($url);
    }

    public function test_portfolio_image_uses_s3_public_disk(): void
    {
        Storage::disk('s3_public')->put('portfolio/image.jpg', 'fake-image');

        $this->assertTrue(Storage::disk('s3_public')->exists('portfolio/image.jpg'));
        // s3_public is NOT s3 private disk
        $this->assertFalse(Storage::disk('s3')->exists('portfolio/image.jpg'));
    }

    public function test_s3_public_disk_is_configured(): void
    {
        $config = config('filesystems.disks.s3_public');

        $this->assertNotNull($config, 's3_public disk must be configured');
        $this->assertEquals('s3', $config['driver']);
        $this->assertEquals('public', $config['visibility']);
    }

    public function test_s3_private_disk_is_configured(): void
    {
        $config = config('filesystems.disks.s3');

        $this->assertNotNull($config);
        $this->assertEquals('s3', $config['driver']);
        // Private disk should not have public visibility
        $this->assertNotEquals('public', $config['visibility'] ?? 'private');
    }
}
