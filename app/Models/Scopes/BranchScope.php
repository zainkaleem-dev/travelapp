<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
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
            $user = auth()->user();
            
            // Bypass for Super Admin, Company Admin, or Unauthenticated users (allows login)
            if (!auth()->check() || ($user && ($user->hasRole('super_admin') || $user->hasRole('company_admin')))) {
                return;
            }

            // Only enforce for Branch Admins or other restricted users
            // We assume branch_id is stored on the user object
            $branchId = $user ? (int) ($user->branch_id ?? 0) : 0;

            if ($branchId > 0) {
                $column = $model instanceof \App\Models\Branch ? $model->getKeyName() : 'branch_id';
                $builder->where($model->getTable() . '.' . $column, $branchId);
            } else {
                // If no branch_id is found for a non-admin, restrict access
                $builder->whereRaw('1 = 0');
            }
        } finally {
            static::$isResolving = false;
        }
    }
}
