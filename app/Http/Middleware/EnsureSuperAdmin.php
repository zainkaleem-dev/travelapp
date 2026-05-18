<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If the original session owner was a Super Admin, bypass this check
        $stack = session()->get('impersonated_by', []);
        $originalUserId = !empty($stack) ? $stack[0] : null;
        $isOriginalSuperAdmin = false;
        if ($originalUserId) {
            $originalUser = \App\Models\User::withoutGlobalScopes()->find($originalUserId);
            if ($originalUser && $originalUser->hasRole('Super Admin')) {
                $isOriginalSuperAdmin = true;
            }
        }

        if ($isOriginalSuperAdmin) {
            return $next($request);
        }

        if (!$user || (!$user->can('Manage Global System') && !$user->hasPermissionTo('Manage Roles and Permissions'))) {
            abort(403);
        }

        return $next($request);
    }
}

