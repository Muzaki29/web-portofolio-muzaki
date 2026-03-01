<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set application locale from session (for ID/EN language toggle).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', 'id');
        if (in_array($locale, ['id', 'en'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
