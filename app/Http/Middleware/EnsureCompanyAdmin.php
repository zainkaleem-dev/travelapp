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
        if (!$user || (bool) ($user->is_super_admin ?? false)) {
            abort(403);
        }
 
        abort_unless(method_exists($user, 'hasRole') && $user->hasRole('company_admin'), 403);
 
        return $next($request);
    }
}

