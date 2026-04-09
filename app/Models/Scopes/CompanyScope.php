<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        try {
            if (!function_exists('request') || !request()) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        /** @var TenantContext $tenantContext */
        $tenantContext = app(TenantContext::class);
        $companyId = $tenantContext->companyId();

        $user = auth()->user();
        $isTenantUser = $user && !(bool) ($user->is_super_admin ?? false);

        if (!$companyId) {
            if ($isTenantUser) {
                $builder->whereRaw('1 = 0');
            }
            return;
        }

        $builder->where($model->getTable() . '.company_id', $companyId);
    }
}

