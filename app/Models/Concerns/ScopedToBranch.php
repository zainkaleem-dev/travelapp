<?php

namespace App\Models\Concerns;

use App\Models\Scopes\BranchScope;

trait ScopedToBranch
{
    protected static function bootScopedToBranch(): void
    {
        static::addGlobalScope(new BranchScope());

        static::creating(function ($model): void {
            if (isset($model->branch_id) && (int) $model->branch_id > 0) {
                return;
            }

            $user = auth()->user();
            $branchId = $user ? (int) ($user->branch_id ?? 0) : 0;

            if ($branchId > 0) {
                $model->branch_id = $branchId;
            }
        });
    }
}
