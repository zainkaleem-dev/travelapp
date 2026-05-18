<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class TmcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a TMC Organization (company)
        $company = Company::updateOrCreate(
            ['slug' => 'tmc-organization'],
            [
                'name' => 'TMC Organization',
                'legal_name' => 'TMC Organization LLC',
                'registration_number' => 'TP-001',
                'company_type' => 'TMC',
                'founded_year' => 2020,
                'status' => 'active',
            ]
        );

        // 2. Create a branch for the TMC Organization
        $branch = Branch::firstOrCreate(
            ['slug' => 'tmc-organization-hq'],
            [
                'company_id' => $company->id,
                'name' => 'TMC Organization HQ',
                'code' => 'TP-HQ',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 3. Create a TMC Organization admin user
        $tmcAdmin = User::firstOrCreate(
            ['email' => 'tmc-organization-admin@example.com'],
            [
                'first_name' => 'TMC',
                'last_name' => 'Organization Admin',
                'password' => 'password',
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
        
        // Give this role all standard permissions
        $role->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());

        $tmcAdmin->assignRole($role);

        // 5. Create Corporate Partner (Child of TMC Organization)
        $childCorp = Company::updateOrCreate(
            ['slug' => 'corporate-partner'],
            [
                'parent_id' => $company->id,
                'name' => 'Corporate Partner',
                'legal_name' => 'Corporate Partner LLC',
                'registration_number' => 'CC-001',
                'company_type' => 'Corporate',
                'founded_year' => 2021,
                'status' => 'active',
            ]
        );

        // 6. Create a branch for the Corporate Partner
        $childBranch = Branch::firstOrCreate(
            ['slug' => 'corporate-partner-hq'],
            [
                'company_id' => $childCorp->id,
                'name' => 'Headquarters',
                'code' => 'CC-HQ',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 7. Create a Corporate Partner admin user
        $corpAdmin = User::firstOrCreate(
            ['email' => 'corporate-partner-admin@example.com'],
            [
                'first_name' => 'Corporate',
                'last_name' => 'Partner Admin',
                'password' => 'password',
                'company_id' => $childCorp->id,
                'branch_id' => $childBranch->id,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
            ]
        );

        // 8. Assign the role within the Corporate Partner company context
        setPermissionsTeamId($childCorp->id);
        
        $childRoleName = is_null($childCorp->parent_id) ? 'Organization Admin' : 'Partner Admin';

        // Ensure the role exists for this company
        $corpRole = Role::firstOrCreate(
            ['name' => $childRoleName, 'company_id' => $childCorp->id, 'guard_name' => 'web'],
            ['status' => 1]
        );
        
        // Sync permissions
        $corpRole->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());

        $corpAdmin->assignRole($corpRole);

        $this->command->info('✅ TMC Organization and Corporate Partner (Corporate Partner) created with admins!');
        $this->command->info('TMC Organization Admin: tmc-organization-admin@example.com / password');
        $this->command->info('Corporate Partner Admin: corporate-partner-admin@example.com / password');
    }
}
