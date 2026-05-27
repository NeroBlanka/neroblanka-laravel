<?php

use App\Http\Middleware\CaptureUtm;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAdminMfa;
use App\Http\Middleware\EnsureClient;
use App\Http\Middleware\EnsureFreelance;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\TrustProxies;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'admin.mfa' => EnsureAdminMfa::class,
            'client' => EnsureClient::class,
            'freelance' => EnsureFreelance::class,
        ]);
        $middleware->web(append: [CaptureUtm::class]);
        $middleware->append(ContentSecurityPolicy::class);
        // Trust Cloudflare + Railway reverse proxies for real client IP
        $middleware->trustProxies(at: '*', headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR | \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST | \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT | \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO | \Illuminate\Http\Request::HEADER_X_FORWARDED_PREFIX);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
