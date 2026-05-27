<?php

namespace Tests\Feature;

use App\Http\Middleware\CaptureUtm;
use App\Models\FunnelEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class FunnelTrackingTest extends TestCase
{
    use RefreshDatabase;

    private function runMiddleware(string $url): void
    {
        $request = Request::create($url);
        $middleware = new CaptureUtm();
        $middleware->handle($request, fn($r) => response('ok'));
    }

    public function test_utm_first_touch_is_not_overwritten_on_second_visit(): void
    {
        $this->runMiddleware('/?utm_source=google&utm_medium=cpc&utm_campaign=launch');

        $this->assertEquals('google', session('utm_first')['utm_source'] ?? null);

        $this->runMiddleware('/?utm_source=facebook&utm_medium=social&utm_campaign=retarget');

        // utm_first must not change
        $this->assertEquals('google', session('utm_first')['utm_source'] ?? null);
        // utm_last is updated
        $this->assertEquals('facebook', session('utm_last')['utm_source'] ?? null);
    }

    public function test_utm_last_is_always_updated(): void
    {
        $this->runMiddleware('/?utm_source=google&utm_medium=cpc&utm_campaign=a');
        $this->runMiddleware('/?utm_source=email&utm_medium=newsletter&utm_campaign=b');

        $this->assertEquals('email', session('utm_last')['utm_source'] ?? null);
    }

    public function test_visit_without_utm_does_not_set_first_touch(): void
    {
        $this->runMiddleware('/');

        $this->assertNull(session('utm_first'));
    }

    public function test_funnel_event_stores_hashed_ip_not_raw(): void
    {
        FunnelEvent::create([
            'event' => 'brief_started',
            'session_id' => 'test-session',
            'ip' => 'abc123hashed00x1', // 16-char hash format
        ]);

        $event = FunnelEvent::first();
        // Must be 16 chars, not a raw IPv4 format
        $this->assertEquals(16, strlen($event->ip));
        $this->assertDoesNotMatchRegularExpression('/^\d{1,3}\.\d{1,3}/', $event->ip);
    }
}
