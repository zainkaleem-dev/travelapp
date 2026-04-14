<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'company_admin']);
        Role::firstOrCreate(['name' => 'branch_admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->command->info('Default roles created successfully.');
    }
}
