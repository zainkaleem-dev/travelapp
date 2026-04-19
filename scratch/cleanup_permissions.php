<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "--- SYSTEM WIDE PERMISSION CLEANUP ---\n";

$targetRoles = ['Organization Admin', 'Branch Admin', 'Agent', 'User'];
$permissionToRevoke = 'Create Branch';

$permission = Permission::where('name', $permissionToRevoke)->first();
if (!$permission) die("Permission '$permissionToRevoke' not found.\n");

$roles = Role::whereIn('name', $targetRoles)
    ->whereNotNull('company_id') // Only touch company-scoped roles
    ->get();

echo "Found " . $roles->count() . " roles to audit.\n";

$revokedCount = 0;
foreach ($roles as $role) {
    // Check if link exists in pivot
    $exists = DB::table('role_has_permissions')
        ->where('role_id', $role->id)
        ->where('permission_id', $permission->id)
        ->exists();

    if ($exists) {
        echo "Revoking '$permissionToRevoke' from Role: '{$role->name}' (ID: {$role->id}, Company: {$role->company_id})\n";
        
        // Surgical revoke
        DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('permission_id', $permission->id)
            ->delete();
            
        $revokedCount++;
    }
}

echo "Cleanup complete. Revoked from $revokedCount roles.\n";

// Clear Spatie cache globally
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Permission cache cleared.\n";
