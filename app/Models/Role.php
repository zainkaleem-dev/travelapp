<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'company_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Override Spatie's permissions relationship to include company_id in the pivot.
     * This ensures that sync() and detach() operations respect the multi-tenant context.
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            config('permission.column_names.role_pivot_key') ?? 'role_id',
            config('permission.column_names.permission_pivot_key') ?? 'permission_id'
        )->withPivot('company_id')
         ->wherePivot('company_id', getPermissionsTeamId());
    }
}
