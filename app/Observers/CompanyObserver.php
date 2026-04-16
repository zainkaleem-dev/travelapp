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

        // 2. Define Default Permissions Sets (match PermissionSeeder logic)
        $allPermissions = [
            'View Dashboard',
            'View Company',
            'Create Company',
            'Edit Company',
            'View Branch',
            'Create Branch',
            'Edit Branch',
            'View User',
            'Create User',
            'Edit User',
            'Manage Roles and Permissions',
            'Manage Features',
        ];

        $companyAdmin = Role::firstOrCreate([
            'name' => 'Company Admin',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $companyAdmin->syncPermissions($allPermissions);

        $branchAdmin = Role::firstOrCreate([
            'name' => 'Branch Admin',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $branchAdmin->syncPermissions($allPermissions);

        $agent = Role::firstOrCreate([
            'name' => 'Agent',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $agent->syncPermissions($allPermissions);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $userRole->syncPermissions($allPermissions);

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}
