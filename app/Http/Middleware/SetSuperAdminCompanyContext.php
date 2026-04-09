<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSuperAdminCompanyContext
{
    /**
     * Persist selected company id in session when present in route.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $company = $request->route('company');

        if ($company && isset($company->id)) {
            $companyId = (int) $company->id;
            $request->session()->put('super_admin_company_id', $companyId);

            try {
                /** @var TenantContext $tenantContext */
                $tenantContext = app(TenantContext::class);
                $tenantContext->setCompanyId($companyId);
            } catch (\Throwable) {
                // ignore
            }
        }

        return $next($request);
    }
}
