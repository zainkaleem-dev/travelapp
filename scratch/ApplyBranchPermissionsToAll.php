<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- APPLY BRANCH PERMISSIONS TO ALL ROLES ---\n";

$permissions = [6, 7]; // Create Branch, Edit Branch
$roles = DB::table('roles')->get();

foreach ($roles as $role) {
    echo "Processing Role: '{$role->name}' (ID: {$role->id}) | Company: " . ($role->company_id ?? 'NULL') . "\n";
    
    foreach ($permissions as $permId) {
        $exists = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('permission_id', $permId)
            ->exists();
            
        if (!$exists) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permId,
                'company_id' => $role->company_id, // properly propagate context
            ]);
            echo "  Assigned Permission {$permId}\n";
        } else {
            echo "  Permission {$permId} already assigned.\n";
        }
    }
}

// Clear Spatie cache
\Spatie\Permission\PermissionRegistrar::class;
app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
echo "Spatie cache cleared.\n";
