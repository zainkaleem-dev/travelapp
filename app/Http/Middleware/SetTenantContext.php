<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);

        $companyId = $tenantContext->resolveCompanyId($request, $request->user());
        $tenantContext->setCompanyId($companyId);

        return $next($request);
    }
}

