<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureUtm
{
    private const UTM_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

    public function handle(Request $request, Closure $next): Response
    {
        $utm = array_filter($request->only(self::UTM_PARAMS));

        if (! empty($utm)) {
            // Preserve first-touch — never overwrite once set
            if (! session()->has('utm_first')) {
                session([
                    'utm_first' => $utm,
                    'landing_page' => $request->path(),
                    'referrer_first' => $request->header('referer'),
                ]);
            }

            // Always update last-touch
            session([
                'utm' => $utm,
                'utm_last' => $utm,
                'referrer_last' => $request->header('referer'),
            ]);
        }

        return $next($request);
    }
}
