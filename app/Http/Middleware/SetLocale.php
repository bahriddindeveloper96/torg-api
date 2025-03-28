<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');
        
        if (!$locale) {
            $locale = config('app.locale');
        } else {
            // Extract primary language from Accept-Language header
            $locale = explode(',', $locale)[0];
            $locale = explode(';', $locale)[0];
            $locale = trim($locale);
        }

        // Check if locale is supported
        if (!in_array($locale, config('app.supported_locales', ['uz', 'ru', 'en']))) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        
        return $next($request);
    }
}
