<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Inject security headers on every HTTP response.
     * Protects against: Clickjacking, XSS, MIME sniffing, data leakage.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking — only allow same-origin framing
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS filter (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer info sent to external sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict browser features / permissions
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // Force HTTPS for 1 year (only in production)
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Remove server information leakage
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
