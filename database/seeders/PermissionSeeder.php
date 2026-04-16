<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. FORCE Global Context (Team ID = NULL)
        // This prevents collisions if the seeder is run while a company context is active.
        setPermissionsTeamId(null);

        // 3. Clean Start (Optional but recommended for this specific seeder setup)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 4. Standard Permissions
        $permissions = [
            'view-dashboard',
            'manage-companies',
            'manage-branches',
            'manage-users',
            'manage-roles',
            'view-leads',
            'create-leads',
            'edit-leads',
            'delete-leads',
            'view-bookings',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 5. Global Roles (Explicitly NULL company_id)
        $superAdmin = \App\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web', 'company_id' => null]);
        $superAdmin->syncPermissions($permissions);

        $companyAdmin = \App\Models\Role::firstOrCreate(['name' => 'Company Admin', 'guard_name' => 'web', 'company_id' => null]);
        $companyAdmin->syncPermissions([
            'view-dashboard',
            'manage-branches',
            'manage-users',
            'view-leads',
            'create-leads',
            'edit-leads',
        ]);

        $branchAdmin = \App\Models\Role::firstOrCreate(['name' => 'Branch Admin', 'guard_name' => 'web', 'company_id' => null]);
        $branchAdmin->syncPermissions([
            'view-dashboard',
            'view-leads',
            'create-leads',
            'edit-leads',
        ]);

        $agent = \App\Models\Role::firstOrCreate(['name' => 'Agent', 'guard_name' => 'web', 'company_id' => null]);
        $agent->syncPermissions([
            'view-leads',
            'create-leads',
        ]);

        $user = \App\Models\Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web', 'company_id' => null]);

        // 6. Ensure Base Admin User exists and has role
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role globally
        setPermissionsTeamId(null);
        $admin->assignRole($superAdmin);
    }
}
