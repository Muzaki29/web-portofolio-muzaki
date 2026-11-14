<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records one row per landing-page view for the admin dashboard.
 * Skips bots, prefetches and logged-in admins; the IP is only kept as a daily salted hash.
 */
class TrackVisit
{
    private const BOTS = '/bot|crawl|spider|slurp|facebookexternalhit|preview|headless|lighthouse|curl|wget|python|monitor/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            rescue(fn () => PageVisit::create([
                'visited_on' => now()->toDateString(),
                'visitor_hash' => hash('sha256', $request->ip().'|'.now()->toDateString().'|'.config('app.key')),
                'referrer_host' => $this->referrerHost($request),
                'device' => preg_match('/Mobi|Android|iPhone/i', (string) $request->userAgent()) ? 'mobile' : 'desktop',
                'locale' => app()->getLocale(),
                'path' => '/'.ltrim($request->path(), '/'),
            ]), report: false);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        return $request->isMethod('GET')
            && $response->getStatusCode() === 200
            && ! $request->user()
            && ! in_array($request->header('Purpose'), ['prefetch'], true)
            && ! preg_match(self::BOTS, (string) $request->userAgent());
    }

    private function referrerHost(Request $request): ?string
    {
        $host = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);

        return $host && $host !== $request->getHost() ? substr($host, 0, 255) : null;
    }
}
