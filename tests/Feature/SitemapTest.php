<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_renders_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('<urlset', false);
        $response->assertSee(url('/'), false);
        $response->assertSee(url('/services'), false);
    }

    public function test_sitemap_includes_resolvable_service_pages(): void
    {
        // La closure itère ServiceType::cases() et n'inclut que les slugs
        // résolvables (fromSlug != null) — confirme que slug()/fromSlug() fonctionnent.
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee('/services/', false);
    }
}
