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

        $basePermissions = [
            'View Dashboard',
            'View Company',
            'View Branch',
            'View User',
        ];

        $managementPermissions = [
            'Create Company',
            'Edit Company',
            'Create User',
            'Edit User',
            'Manage Roles and Permissions',
            'Manage Features',
        ];

        $executivePermissions = [
            'Create Branch',
            'Edit Branch',
        ];

        // 3. Define Roles and their specific permission mappings
        $roleDefinitions = [
            'Company Admin'      => array_merge($basePermissions, $managementPermissions, $executivePermissions),
            'Organization Admin' => array_merge($basePermissions, $managementPermissions),
            'Branch Admin'       => array_merge($basePermissions, $managementPermissions),
            'Agent'             => $basePermissions,
            'User'              => $basePermissions,
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

