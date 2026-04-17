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

        // 2. Define Default Permissions Sets
        $allPermissions = [
            'View Dashboard', 'View Company', 'Create Company', 'Edit Company',
            'View Branch', 'Create Branch', 'Edit Branch', 'View User',
            'Create User', 'Edit User', 'Manage Roles and Permissions', 'Manage Features',
        ];

        // Define explicit safeguard to ensure the Master Key is NEVER assigned during company creation
        $safePermissionNames = array_filter($allPermissions, fn($p) => $p !== 'Manage Global System');
        
        // Fetch permission IDs for efficient syncing
        $permissionIds = Permission::whereIn('name', $safePermissionNames)->pluck('id');
        $syncData = $permissionIds->mapWithKeys(fn($id) => [$id => ['company_id' => $company->id]])->toArray();

        // 3. Create Default Roles and Assign Permissions explicitly to pivot
        $roleNames = ['Company Admin', 'Organization Admin', 'Branch Admin', 'Agent', 'User'];

        foreach ($roleNames as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'company_id' => $company->id,
                'status' => 1
            ]);
            
            // Use standard Laravel relationship sync to ensure company_id is populated in the pivot
            $role->permissions()->sync($syncData);
        }

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}
