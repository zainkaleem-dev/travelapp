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

        // 5. Create Partner Companies (Children of Demo Organization)
        
        // Partner 1: Sub-Corporate
        $subCorp = Company::updateOrCreate(
            ['slug' => 'demo-sub-corporate'],
            [
                'parent_id' => $company->id,
                'name' => 'Demo Sub-Corporate',
                'company_type' => 'Corporate',
                'status' => 'active',
            ]
        );

        // Partner 2: Sub-TMC
        $subTmc = Company::updateOrCreate(
            ['slug' => 'demo-sub-tmc'],
            [
                'parent_id' => $company->id,
                'name' => 'Demo Sub-TMC',
                'company_type' => 'TMC',
                'status' => 'active',
            ]
        );

        // Create Users for Partners
        $partners = [
            [
                'company' => $subCorp,
                'email' => 'partner-corp@demo.com',
                'first_name' => 'Corporate',
                'last_name' => 'Partner',
                'role' => 'Partner Admin',
            ],
            [
                'company' => $subCorp,
                'email' => 'agent-corp@demo.com',
                'first_name' => 'Corporate',
                'last_name' => 'Agent',
                'role' => 'Agent',
            ],
            [
                'company' => $subTmc,
                'email' => 'partner-tmc@demo.com',
                'first_name' => 'TMC',
                'last_name' => 'Partner',
                'role' => 'Partner Admin',
            ],
            [
                'company' => $subTmc,
                'email' => 'agent-tmc@demo.com',
                'first_name' => 'TMC',
                'last_name' => 'Agent',
                'role' => 'Agent',
            ]
        ];

        foreach ($partners as $pData) {
            $pComp = $pData['company'];
            
            // Create a branch for the partner
            $pBranch = Branch::firstOrCreate(
                ['slug' => $pComp->slug . '-hq'],
                [
                    'company_id' => $pComp->id,
                    'name' => 'Headquarters',
                    'code' => strtoupper(substr($pComp->slug, 5, 3)) . '-01',
                    'is_main' => true,
                    'status' => 'active',
                ]
            );

            $pUser = User::firstOrCreate(
                ['email' => $pData['email']],
                [
                    'first_name' => $pData['first_name'],
                    'last_name' => $pData['last_name'],
                    'password' => 'password',
                    'company_id' => $pComp->id,
                    'branch_id' => $pBranch->id,
                    'email_verified_at' => Carbon::now(),
                    'status' => 'active',
                ]
            );

            // Set context for the partner company
            setPermissionsTeamId($pComp->id);
            
            $pRole = Role::firstOrCreate(
                ['name' => $pData['role'], 'company_id' => $pComp->id, 'guard_name' => 'web'],
                ['status' => 1]
            );
            
            // Sync permissions based on role
            if ($pData['role'] === 'Partner Admin') {
                $pRole->syncPermissions(\Spatie\Permission\Models\Permission::whereNotIn('name', ['Manage Global System'])->get());
            } else {
                // Agents get restricted permissions (e.g. only view/create things related to flights/users)
                $pRole->syncPermissions(\Spatie\Permission\Models\Permission::whereIn('name', ['View Users', 'View Company'])->get());
            }
            
            $pUser->assignRole($pRole);
        }

        $this->command->info('✅ Dummy organization, partners, branches, and admin users created!');
        $this->command->info('Org Admin: orgadmin@demo.com / password');
        $this->command->info('Corp Partner Admin: partner-corp@demo.com / password');
        $this->command->info('Corp Agent: agent-corp@demo.com / password');
        $this->command->info('TMC Partner Admin: partner-tmc@demo.com / password');
        $this->command->info('TMC Agent: agent-tmc@demo.com / password');
    }
}
