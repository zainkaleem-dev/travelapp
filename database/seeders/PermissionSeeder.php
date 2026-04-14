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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Standard Permissions
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
            \Spatie\Permission\Models\Permission::findOrCreate($permission, 'web');
        }

        // Global Roles (No company_id)
        $superAdmin = \App\Models\Role::findOrCreate('super_admin', 'web');
        $superAdmin->syncPermissions($permissions); // Super Admin gets everything

        // Sample System Roles
        $companyAdmin = \App\Models\Role::findOrCreate('company_admin', 'web');
        $companyAdmin->syncPermissions([
            'view-dashboard',
            'manage-branches',
            'manage-users',
            'view-leads',
            'create-leads',
            'edit-leads',
        ]);

        $branchAdmin = \App\Models\Role::findOrCreate('branch_admin', 'web');
        $branchAdmin->syncPermissions([
            'view-dashboard',
            'view-leads',
            'create-leads',
            'edit-leads',
        ]);

        $agent = \App\Models\Role::findOrCreate('agent', 'web');
        $agent->syncPermissions([
            'view-leads',
            'create-leads',
        ]);
    }
}
