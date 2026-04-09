<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SuperAdminSelectedCompanyScope implements Scope
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

        if (!request()->is('super-admin*')) {
            return;
        }

        $user = auth()->user();
        if (!$user || !(bool) ($user->is_super_admin ?? false)) {
            return;
        }

        $selectedCompanyId = (int) session('super_admin_company_id', 0);
        if ($selectedCompanyId <= 0) {
            $builder->whereRaw('1 = 0');
            return;
        }

        $builder->where($model->getTable() . '.company_id', $selectedCompanyId);
    }
}

