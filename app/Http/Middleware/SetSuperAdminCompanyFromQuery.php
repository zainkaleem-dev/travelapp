<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSuperAdminCompanyFromQuery
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestedCompanyId = (int) ($request->query('company') ?? $request->query('company_id') ?? 0);
        if ($requestedCompanyId > 0) {
            $request->session()->put('super_admin_company_id', $requestedCompanyId);

            try {
                /** @var TenantContext $tenantContext */
                $tenantContext = app(TenantContext::class);
                $tenantContext->setCompanyId($requestedCompanyId);
            } catch (\Throwable) {
                // ignore
            }
        }

        return $next($request);
    }
}

