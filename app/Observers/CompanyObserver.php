<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        // 1. Force context to the new company
        setPermissionsTeamId($company->id);

        $standardPermissions = [
            'View Dashboard',
            'View Company',
            'Create Company',
            'Edit Company',
            'Delete Company',
            'View Branch',
            'Create Branch',
            'Edit Branch',
            'Delete Branch',
            'View Users',
            'Create User',
            'Edit User',
            'Delete User',
            'Manage Roles and Permissions',
            'Manage Features',
        ];


        $roleDefinitions = [
            'Company Admin' => $standardPermissions,
            'Organization Admin' => $standardPermissions,
            'Branch Admin' => $standardPermissions,
            'Agent' => $standardPermissions,
            'User' => $standardPermissions,
        ];

        foreach ($roleDefinitions as $roleName => $perms) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'company_id' => $company->id,
                'status' => 1
            ]);

            // Sync permissions for this specific role context
            $permissionIds = Permission::whereIn('name', $perms)
                ->where('name', '!=', 'Manage Global System')
                ->pluck('id');

            $syncData = $permissionIds->mapWithKeys(fn($id) => [$id => ['company_id' => $company->id]])->toArray();
            $role->permissions()->sync($syncData);
        }

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}

