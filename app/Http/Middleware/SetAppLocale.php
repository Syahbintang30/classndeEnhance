<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
            $lang = strtolower((string)$request->query('lang'));
            if (in_array($lang, ['id', 'en'])) {
                session(['app_lang' => $lang]);
            }
        }

        $locale = session('app_lang', config('app.locale', 'id'));
        App::setLocale($locale);

        return $next($request);
    }
}
