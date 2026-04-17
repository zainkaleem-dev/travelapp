<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureUser
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user->can('View Dashboard'), 403);
 
        abort_unless((int) ($user->company_id ?? 0) > 0, 403);
 
        return $next($request);
    }
}
