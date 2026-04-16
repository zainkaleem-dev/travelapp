<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureCompanyAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->hasRole('Super Admin')) {
            return $next($request);
        }

        abort_unless($user->hasRole('Company Admin'), 403);
 
        abort_unless((int) ($user->company_id ?? 0) > 0, 403);
 
        return $next($request);
    }
}

