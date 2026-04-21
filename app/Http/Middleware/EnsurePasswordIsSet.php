<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            !$user->has_set_password &&
            !$user->hasRole('Super Admin') &&
            !$request->routeIs('password.setup') &&
            !$request->routeIs('logout')
        ) {
            return redirect()->route('password.setup');
        }

        return $next($request);
    }
}

