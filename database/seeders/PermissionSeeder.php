<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // 4. Standard Permissions
        $standardPermissions = [
            // dashboard
            'View Dashboard',

            // company
            'View Company',
            'Create Company',
            'Edit Company',
            'Delete Company',

            // branch
            'View Branch',
            'Create Branch',
            'Edit Branch',
            'Delete Branch',

            // user
            'View Users',
            'Create User',
            'Edit User',
            'Delete User',
            'Manage Roles and Permissions',
            'Manage Features',

            // Airports
            'View Airport',
            'Create Airport',
            'Edit Airport',
            'Delete Airport',

            // Countries and Cities
            'View Countries and Cities',
            'Create Countries and Cities',
            'Edit Countries and Cities',
            'Delete Countries and Cities',

            // Subscriptions
            'View Subscription',
            'Create Subscription',
            'Edit Subscription',
            'Delete Subscription',

            // Travel Policies
            'View Travel Policy',
            'Create Travel Policy',
            'Edit Travel Policy',
            'Delete Travel Policy',

            // Trip Purposes
            'View Trip Purpose',
            'Create Trip Purpose',
            'Edit Trip Purpose',
            'Delete Trip Purpose',

            // System Settings
            'View System Setting',
        ];

        $globalPermissions = [
            'Manage Global System',
        ];

        foreach (array_merge($standardPermissions, $globalPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 5. Global Roles (Explicitly NULL company_id)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web', 'company_id' => null, 'status' => 1]);
        // Super Admin gets EVERYTHING including Global System management
        $superAdmin->syncPermissions(array_merge($standardPermissions, $globalPermissions));

        // 6. Ensure Base Admin User exists and has role
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role globally
        setPermissionsTeamId(null);
        $admin->assignRole($superAdmin);
    }
}
