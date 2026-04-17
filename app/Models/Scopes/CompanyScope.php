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
            $isSuperAdmin = $user && $user->hasRole('Super Admin');
            
            // Global bypass for Super Admin or Unauthenticated users (allows login)
            if ($isSuperAdmin || !auth()->check()) {
                return;
            }

            if ($companyId === null) {
                // If the context isn't set yet (e.g. during early authentication or login),
                // we skip adding the filter. This allows the user to be found so the context can be set.
                return;
            }

            $builder->where($model->getTable() . '.company_id', $companyId);
        } finally {
            static::$isResolving = false;
        }
    }
}
