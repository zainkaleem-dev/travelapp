<?php
 
namespace App\Http\Middleware;
 
use App\Models\SubCompany;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureSubCompanyAdmin
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
 
        abort_unless(method_exists($user, 'hasRole') && $user->hasRole('subcompany_admin'), 403);
 
        $subCompanyId = (int) ($user->sub_company_id ?? 0);
        abort_unless($subCompanyId > 0, 403);
 
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $subCompany = SubCompany::query()
            ->where('company_id', $companyId)
            ->find($subCompanyId);
        abort_unless((bool) $subCompany, 403);
 
        return $next($request);
    }
}

