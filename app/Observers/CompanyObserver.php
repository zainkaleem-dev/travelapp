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

        // 2. Fetch all available permissions except Global Management
        $allStandardPermissions = Permission::where('name', '!=', 'Manage Global System')->get();
        $permissionIds = $allStandardPermissions->pluck('id');

        $roleDefinitions = [
            'Partner Admin',
            'Organization Admin',
            'Branch Admin',
            'Agent',
            'User',
        ];

        foreach ($roleDefinitions as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'company_id' => $company->id,
                'status' => 1
            ]);

            // Sync all standard permissions for this specific role context
            $syncData = $permissionIds->mapWithKeys(fn($id) => [$id => ['company_id' => $company->id]])->toArray();
            $role->permissions()->sync($syncData);
        }

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}

