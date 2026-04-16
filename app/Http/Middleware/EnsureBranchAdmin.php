<?php
 
namespace App\Http\Middleware;
 
use App\Models\Branch;
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
        if (!$user || $user->hasRole('Super Admin') || $user->hasRole('Company Admin')) {
            return $next($request);
        }

        abort_unless($user->hasRole('Branch Admin'), 403);
 
 
        $branchId = (int) ($user->branch_id ?? 0);
        abort_unless($branchId > 0, 403);
 
        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $branch = Branch::query()
            ->where('company_id', $companyId)
            ->find($branchId);
        abort_unless((bool) $branch, 403);
 
        return $next($request);
    }
}

