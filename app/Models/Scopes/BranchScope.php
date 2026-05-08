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

            // Bypass for Super Admin, Partner Admin, Organization Admin, or Unauthenticated users (allows login)
            $isAdmin = $user && (
                $user->hasRole('Super Admin') || 
                ($user->company_id > 0 && $user->hasRole(['Partner Admin', 'Organization Admin'], null, (int) $user->company_id))
            );

            if (!auth()->check() || $isAdmin) {
                return;
            }

            // Only enforce for Branch Admins or other restricted users
            // We assume branch_id is stored on the user object
            $branchId = $user ? (int) ($user->branch_id ?? 0) : 0;

            if ($branchId > 0) {
                $column = $model instanceof \App\Models\Branch ? $model->getKeyName() : 'branch_id';
                $builder->where($model->getTable() . '.' . $column, $branchId);
            } else {
                // If it's a guest or Super/Partner Admin, they should have been bypassed above.
                // If we reached here, it's a restricted user without a branch context.
                if (!auth()->check())
                    return;

                $builder->whereRaw('1 = 0');
            }
        } finally {
            static::$isResolving = false;
        }
    }
}
