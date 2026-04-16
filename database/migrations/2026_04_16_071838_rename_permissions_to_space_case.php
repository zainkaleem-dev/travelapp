<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'view-dashboard' => 'View Dashboard',
            'manage-companies' => 'Manage Companies',
            'manage-branches' => 'Manage Branches',
            'manage-users' => 'Manage Users',
            'manage-roles' => 'Manage Roles',
            'view-leads' => 'View Leads',
            'create-leads' => 'Create Leads',
            'edit-leads' => 'Edit Leads',
            'delete-leads' => 'Delete Leads',
            'view-bookings' => 'View Bookings',
            'manage-settings' => 'Manage Settings',
        ];

        foreach ($permissions as $old => $new) {
            DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'View Dashboard' => 'view-dashboard',
            'Manage Companies' => 'manage-companies',
            'Manage Branches' => 'manage-branches',
            'Manage Users' => 'manage-users',
            'Manage Roles' => 'manage-roles',
            'View Leads' => 'view-leads',
            'Create Leads' => 'create-leads',
            'Edit Leads' => 'edit-leads',
            'Delete Leads' => 'delete-leads',
            'View Bookings' => 'view-bookings',
            'Manage Settings' => 'manage-settings',
        ];

        foreach ($permissions as $old => $new) {
            DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }
    }
};
