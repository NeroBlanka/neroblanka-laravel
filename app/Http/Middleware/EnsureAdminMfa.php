<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminMfa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->totp_enabled) {
            return $next($request);
        }

        if (! session('admin_mfa_passed')) {
            session()->put('url.intended', $request->url());
            return redirect()->route('admin.mfa.verify');
        }

        return $next($request);
    }
}
