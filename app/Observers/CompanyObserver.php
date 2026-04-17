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

        // Define explicit safeguard to ensure the Master Key is NEVER assigned during company creation
        $safePermissions = array_filter($allPermissions, fn($p) => $p !== 'Manage Global System');

        // 3. Create Default Roles for the Company
        $companyAdmin = Role::firstOrCreate([
            'name' => 'Company Admin',
            'guard_name' => 'web',
            'company_id' => $company->id,
            'status' => 1
        ]);
        $companyAdmin->syncPermissions($safePermissions);

        $branchAdmin = Role::firstOrCreate([
            'name' => 'Branch Admin',
            'guard_name' => 'web',
            'company_id' => $company->id,
            'status' => 1
        ]);
        $branchAdmin->syncPermissions($safePermissions);

        $agent = Role::firstOrCreate([
            'name' => 'Agent',
            'guard_name' => 'web',
            'company_id' => $company->id,
            'status' => 1
        ]);
        $agent->syncPermissions($safePermissions);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
            'company_id' => $company->id,
            'status' => 1
        ]);
        $userRole->syncPermissions($safePermissions);

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}
