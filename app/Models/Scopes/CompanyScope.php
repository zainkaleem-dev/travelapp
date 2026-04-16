<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    protected static bool $isResolving = false;

    public function apply(Builder $builder, Model $model): void
    {
        if (static::$isResolving) {
            return;
        }

        try {
            if (!function_exists('request') || !request()) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        static::$isResolving = true;

        try {
            /** @var TenantContext $tenantContext */
            $tenantContext = app(TenantContext::class);
            $companyId = $tenantContext->companyId();

            $user = auth()->user();
            $isSuperAdmin = $user && $user->hasRole('super_admin');
            
            // Global bypass for Super Admin or Unauthenticated users (allows login)
            if ($isSuperAdmin || !auth()->check()) {
                return;
            }

            if ($companyId === null) {
                // If the user is authenticated but no company is resolved, 
                // and they aren't a super admin, we should restrict unless we are in a truly global context.
                $builder->whereRaw('1 = 0');
                return;
            }

            $builder->where($model->getTable() . '.company_id', $companyId);
        } finally {
            static::$isResolving = false;
        }
    }
}
