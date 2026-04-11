<?php
 
namespace App\Http\Middleware;
 
use App\Models\CompanyBranch;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureBranchAdmin
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
 
        abort_unless(method_exists($user, 'hasRole') && $user->hasRole('branch_admin'), 403);
 
        $branchId = (int) ($user->company_branch_id ?? 0);
        abort_unless($branchId > 0, 403);
 
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $branch = CompanyBranch::query()
            ->where('company_id', $companyId)
            ->find($branchId);
        abort_unless((bool) $branch, 403);
 
        return $next($request);
    }
}

