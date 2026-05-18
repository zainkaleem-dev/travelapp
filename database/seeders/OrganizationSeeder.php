<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Corporate Organization (company)
        $company = Company::updateOrCreate(
            ['slug' => 'corporate-organization'],
            [
                'name' => 'Corporate Organization',
                'legal_name' => 'Corporate Organization LLC',
                'registration_number' => 'CP-001',
                'company_type' => 'Corporate',
                'founded_year' => 2022,
                'status' => 'active',
            ]
        );

        // 2. Create a branch for the Corporate Organization
        $branch = Branch::firstOrCreate(
            ['slug' => 'corporate-organization-hq'],
            [
                'company_id' => $company->id,
                'name' => 'Headquarters',
                'code' => 'CP-HQ',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 3. Create a Corporate Organization admin user
        $orgAdmin = User::firstOrCreate(
            ['email' => 'corporate-organization-admin@example.com'],
            [
                'first_name' => 'Corporate',
                'last_name' => 'Organization Admin',
                'password' => 'password', // The User model hashes this automatically via casts
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
            ]
        );

        // 4. Assign the role within the specific company context
        setPermissionsTeamId($company->id);
        
        $roleName = is_null($company->parent_id) ? 'Organization Admin' : 'Partner Admin';

        // Ensure the role exists for this company
        $role = Role::firstOrCreate(
            ['name' => $roleName, 'company_id' => $company->id, 'guard_name' => 'web'],
            ['status' => 1]
        );
        
        // Sync permissions to this role (exclude global system management)
        $role->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());

        $orgAdmin->assignRole($role);

        // 5. Create Child TMC Partner (Child of Corporate Organization)
        $childTmc = Company::updateOrCreate(
            ['slug' => 'tmc-partner'],
            [
                'parent_id' => $company->id,
                'name' => 'TMC Partner',
                'legal_name' => 'TMC Partner LLC',
                'registration_number' => 'TC-001',
                'company_type' => 'TMC',
                'founded_year' => 2023,
                'status' => 'active',
            ]
        );

        // 6. Create a branch for the TMC Partner
        $childBranch = Branch::firstOrCreate(
            ['slug' => 'tmc-partner-hq'],
            [
                'company_id' => $childTmc->id,
                'name' => 'Headquarters',
                'code' => 'TC-HQ',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 7. Create a TMC Partner admin user
        $tmcAdmin = User::firstOrCreate(
            ['email' => 'tmc-partner-admin@example.com'],
            [
                'first_name' => 'TMC',
                'last_name' => 'Partner Admin',
                'password' => 'password',
                'company_id' => $childTmc->id,
                'branch_id' => $childBranch->id,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
            ]
        );

        // 8. Assign the role within the child TMC Partner company context
        setPermissionsTeamId($childTmc->id);
        
        $childRoleName = is_null($childTmc->parent_id) ? 'Organization Admin' : 'Partner Admin';

        // Ensure the role exists for this company
        $tmcRole = Role::firstOrCreate(
            ['name' => $childRoleName, 'company_id' => $childTmc->id, 'guard_name' => 'web'],
            ['status' => 1]
        );
        
        // Sync permissions (exclude global system management)
        $tmcRole->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());

        $tmcAdmin->assignRole($tmcRole);

        $this->command->info('✅ Corporate Organization and child TMC Partner (TMC Partner) created with admins!');
        $this->command->info('Corporate Organization Admin: corporate-organization-admin@example.com / password');
        $this->command->info('TMC Partner Admin: tmc-partner-admin@example.com / password');
    }
}
