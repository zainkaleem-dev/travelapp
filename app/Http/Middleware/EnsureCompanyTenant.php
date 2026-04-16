<?php
 
namespace App\Http\Middleware;
 
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureCompanyTenant
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->hasRole('Super Admin')) {
            abort(403);
        }
 
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        abort_unless((bool) $tenantContext->companyId(), 403);
 
        return $next($request);
    }
}

