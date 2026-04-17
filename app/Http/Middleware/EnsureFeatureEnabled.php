<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        // 1. Super Admin Bypass: They always see everything
        if ($user && $user->can('Manage Global System')) {
            return $next($request);
        }

        // 2. Feature Entitlement Check
        // Pennant automatically uses the scope resolved in AppServiceProvider
        if (! Feature::active($feature)) {
            abort(403, "The requested feature module ({$feature}) is not enabled for your company.");
        }

        return $next($request);
    }
}
