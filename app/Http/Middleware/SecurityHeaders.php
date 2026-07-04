<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Inject comprehensive security headers on every HTTP response.
     * Protects against: Clickjacking, XSS, MIME sniffing, data leakage,
     * code injection, protocol downgrade, and information disclosure.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ── Anti-Clickjacking ──
        $response->headers->set('X-Frame-Options', 'DENY');

        // ── Prevent MIME type sniffing ──
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── XSS filter (legacy browsers) ──
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Referrer Policy — minimize data leakage ──
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── Permissions Policy — disable unused browser APIs ──
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), ' .
            'usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ' .
            'autoplay=(), fullscreen=(self), display-capture=()'
        );

        // ── Content Security Policy (CSP) — primary XSS defense ──
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https:",
            "connect-src 'self' blob: data:",
            "frame-src 'none'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // ── Cross-Origin Policies — isolate from other origins ──
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'credentialless');

        // ── Force HTTPS (production only) ──
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        // ── Prevent caching of sensitive pages ──
        if ($request->isMethod('POST')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        // ── Remove server information leakage ──
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
