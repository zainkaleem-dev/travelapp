<?php
 
namespace App\Http\Middleware;
 
use App\Models\SubCompanyBranch;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureSubCompanyBranchAdmin
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
 
        abort_unless(method_exists($user, 'hasRole') && $user->hasRole('subcompany_branch_admin'), 403);
 
        $subCompanyBranchId = (int) ($user->sub_company_branch_id ?? 0);
        abort_unless($subCompanyBranchId > 0, 403);
 
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $subCompanyBranch = SubCompanyBranch::query()
            ->where('company_id', $companyId)
            ->find($subCompanyBranchId);
        abort_unless((bool) $subCompanyBranch, 403);
 
        return $next($request);
    }
}

