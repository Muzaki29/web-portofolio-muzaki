<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global API rate limiter — 60 requests per minute per IP
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Strict chatbot rate limiter — 12 requests per minute per IP
        RateLimiter::for('chatbot', function (Request $request) {
            return Limit::perMinute(12)->by($request->ip())
                ->response(function (Request $request) {
                    $locale = $request->input('locale', 'id');
                    return response()->json([
                        'success' => false,
                        'message' => $locale === 'en'
                            ? 'Too many requests. Please wait a moment before trying again.'
                            : 'Terlalu banyak permintaan. Silakan tunggu sebentar.',
                    ], 429);
                });
        });

        // Contact form rate limiter — 3 requests per minute per IP
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
