<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- REVOKING BRANCH MANAGEMENT FROM NON-EXECS ---\n";

$permissions = [6, 7]; // Create Branch, Edit Branch
$nonExecRoles = ['Organization Admin', 'Branch Admin', 'Agent', 'User'];

$rolesToRestrict = DB::table('roles')->whereIn('name', $nonExecRoles)->get();

foreach ($rolesToRestrict as $role) {
    echo "Processing Role: '{$role->name}' (ID: {$role->id}) | Company: " . ($role->company_id ?? 'NULL') . "\n";
    
    foreach ($permissions as $permId) {
        $deleted = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('permission_id', $permId)
            ->delete();
            
        if ($deleted) {
            echo "  Revoked Permission {$permId}\n";
        } else {
            echo "  Permission {$permId} was not assigned.\n";
        }
    }
}

// Clear Spatie cache
app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
echo "Spatie cache cleared.\n";
