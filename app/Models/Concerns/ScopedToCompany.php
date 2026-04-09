<?php

namespace App\Models\Concerns;

use App\Models\Scopes\CompanyScope;
use App\Support\TenantContext;

trait ScopedToCompany
{
    protected static function bootScopedToCompany(): void
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model): void {
            if (isset($model->company_id) && (int) $model->company_id > 0) {
                return;
            }

            try {
                /** @var TenantContext $tenantContext */
                $tenantContext = app(TenantContext::class);
                $companyId = $tenantContext->companyId();
            } catch (\Throwable) {
                return;
            }

            if ($companyId && $companyId > 0) {
                $model->company_id = $companyId;
            }
        });
    }
}

