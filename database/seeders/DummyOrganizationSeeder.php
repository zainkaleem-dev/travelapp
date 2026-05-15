<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class DummyOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a dummy organization (company)
        $company = Company::updateOrCreate(
            ['slug' => 'demo-organization'],
            [
                'name' => 'Demo Organization',
                'legal_name' => 'Demo Organization LLC',
                'registration_number' => 'DO-001',
                'company_type' => 'Corporate',
                'founded_year' => 2022,
                'status' => 'active',
            ]
        );

        // 2. Create a dummy branch for the organization
        $branch = Branch::firstOrCreate(
            ['slug' => 'demo-branch-hq'],
            [
                'company_id' => $company->id,
                'name' => 'Headquarters',
                'code' => 'HQ-01',
                'is_main' => true,
                'status' => 'active',
            ]
        );

        // 3. Create a dummy organization admin user
        $orgAdmin = User::firstOrCreate(
            ['email' => 'orgadmin@demo.com'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'password' => 'password', // The User model will hash this automatically via casts
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
            ]
        );

        // 4. Assign the role within the specific company context
        setPermissionsTeamId($company->id);
        
        // Ensure the role exists for this company
        $role = Role::firstOrCreate(
            ['name' => 'Organization Admin', 'company_id' => $company->id, 'guard_name' => 'web'],
            ['status' => 1]
        );
        
        // Sync permissions to this role (exclude global system management)
        $role->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());

        $orgAdmin->assignRole($role);

        $this->command->info('✅ Dummy organization, branch, and admin user created!');
        $this->command->info('Email: orgadmin@demo.com');
        $this->command->info('Password: password');
    }
}
