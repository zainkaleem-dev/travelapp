<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSuperAdminCompanySelected
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);

        $companyId = $tenantContext->companyId();
        if ($companyId && $companyId > 0) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(422, 'Please select a company first.');
        }

        return redirect()->route('superadmin.companies.index')
            ->with('error', 'Please select a company first.');
    }
}

