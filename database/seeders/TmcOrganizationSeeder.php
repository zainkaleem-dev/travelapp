<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TmcOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a TMC organization (company)
        $company = Company::updateOrCreate(
            ['slug' => 'demo-tmc'],
            [
                'name' => 'Demo TMC',
                'legal_name' => 'Demo Travel Management Company LLC',
                'registration_number' => 'TMC-001',
                'company_type' => 'TMC',
                'founded_year' => 2020,
                'status' => 'active',
                'settings' => [
                    'background_color' => '#1e293b',
                    'foreground_color' => '#ffffff',
                ]
            ]
        );

        // 2. Create a dummy branch for the TMC
        $branch = Branch::firstOrCreate(
            ['slug' => 'tmc-branch-hq'],
            [
                'company_id' => $company->id,
                'name' => 'TMC HQ',
                'code' => 'THQ-01',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 3. Ensure Partner Admin role exists for this company
        setPermissionsTeamId($company->id);
        $role = Role::firstOrCreate(
            ['name' => 'Partner Admin', 'company_id' => $company->id, 'guard_name' => 'web'],
            ['status' => 1]
        );

        // Give this role all standard permissions
        $permissions = Permission::whereNotIn('name', ['Manage Global System'])->get();
        $role->syncPermissions($permissions);

        // 4. Create a dummy TMC admin user
        $tmcAdmin = User::firstOrCreate(
            ['email' => 'tmcadmin@demo.com'],
            [
                'first_name' => 'TMC',
                'last_name' => 'Admin',
                'password' => 'password',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
            ]
        );

        // 5. Assign the role
        $tmcAdmin->assignRole($role);

        $this->command->info('✅ TMC organization, branch, and admin user created!');
        $this->command->info('Email: tmcadmin@demo.com');
        $this->command->info('Password: password');
    }
}
