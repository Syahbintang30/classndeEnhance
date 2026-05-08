<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureAdminOrSuper
{
    /**
     * Handle an incoming request.
     * Allow access if user is_admin OR is_superadmin.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || (! $user->is_admin && ! $user->is_superadmin)) {
            abort(403, 'Unauthorized.');
        }
        // Superadmin has full access
        if ($user->is_superadmin) {
            return $next($request);
        }

        // For regular admin (not superadmin), restrict to a small set of admin routes
        // Allowed patterns for admin users
        $allowed = [
            'admin',
            'superadmin',
            'admin/lessons*',
            'admin/coaching/bookings*',
            'admin/coaching/slot-capacities*',
            'admin/users/packages*',
            'admin/packages*',
            'admin/transactions*',
        ];

        $path = ltrim($request->path(), '/'); // normalize

        foreach ($allowed as $pattern) {
            if (Str::is($pattern, $path)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized.');
    }
}
