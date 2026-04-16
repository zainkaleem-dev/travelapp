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
            'Manage Companies',
            'Manage Branches',
            'Manage Users',
            'Manage Roles',
            'View Leads',
            'Create Leads',
            'Edit Leads',
            'Delete Leads',
            'View Bookings',
            'Manage Settings',
        ];

        $companyAdminPerms = [
            'View Dashboard',
            'Manage Branches',
            'Manage Users',
            'View Leads',
            'Create Leads',
            'Edit Leads',
        ];

        $branchAdminPerms = [
            'View Dashboard',
            'View Leads',
            'Create Leads',
            'Edit Leads',
        ];

        $agentPerms = [
            'View Leads',
            'Create Leads',
        ];

        // 3. Create Default Roles for the Company
        $companyAdmin = Role::firstOrCreate([
            'name' => 'Company Admin',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $companyAdmin->syncPermissions($companyAdminPerms);

        $branchAdmin = Role::firstOrCreate([
            'name' => 'Branch Admin',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $branchAdmin->syncPermissions($branchAdminPerms);

        $agent = Role::firstOrCreate([
            'name' => 'Agent',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);
        $agent->syncPermissions($agentPerms);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
            'company_id' => $company->id
        ]);

        // 4. Reset context to null (Safety)
        setPermissionsTeamId(null);
    }
}
