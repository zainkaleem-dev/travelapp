<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "--- PERMISSION REFLECTION AUDIT ---\n";

$companyId = 1; // Example: Hexafume
$roleName = 'Organization Admin';
$permissionName = 'Create Branch';

// 1. Fetch the specific role for context 1
$role = Role::where('name', $roleName)->where('company_id', $companyId)->first();
if (!$role) { die("Role '$roleName' for Company $companyId not found.\n"); }

echo "Role: {$roleName} (ID: {$role->id}, Company: {$companyId})\n";

// 2. Check if permission is in role_has_permissions
$hasInRole = DB::table('role_has_permissions')
    ->where('role_id', $role->id)
    ->where('permission_id', Permission::where('name', $permissionName)->value('id'))
    ->exists();

echo "Permission '$permissionName' is " . ($hasInRole ? "ENABLED" : "DISABLED (Closed)") . " in role_has_permissions.\n";

// 3. Find a user with this role across any company if company 1 failed
$user = null;
$foundCompanyId = $companyId;

$roleSearch = Role::where('name', $roleName)->get();
foreach ($roleSearch as $r) {
    $u = User::whereHas('roles', function($q) use ($r) {
        $q->where('roles.id', $r->id);
    })->first();
    
    if ($u) {
        $user = $u;
        $role = $r;
        $foundCompanyId = $r->company_id;
        break;
    }
}

if (!$user) {
    echo "No user found with role '$roleName' across any company. Audit restricted.\n";
} else {
    echo "Testing User: {$user->email} (ID: {$user->id}) in Company: {$foundCompanyId}\n";
    $companyId = $foundCompanyId;
    
    // Check if role has it again for the found context
    $hasInRole = DB::table('role_has_permissions')
        ->where('role_id', $role->id)
        ->where('permission_id', Permission::where('name', $permissionName)->value('id'))
        ->exists();
    echo "Permission '$permissionName' is " . ($hasInRole ? "ENABLED" : "DISABLED") . " in role_has_permissions for this role.\n";
    
    // Check direct permissions
    $hasDirect = DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('model_type', User::class)
        ->where('company_id', $companyId)
        ->where('permission_id', Permission::where('name', $permissionName)->value('id'))
        ->exists();
    
    echo "Permission '$permissionName' is " . ($hasDirect ? "ENABLED" : "DISABLED") . " in model_has_permissions (Direct).\n";

    // 4. Simulate the GATE check
    setPermissionsTeamId($companyId);
    $canCheck = $user->can($permissionName);
    
    echo "Final Result: user->can('$permissionName') returns: " . ($canCheck ? "TRUE (Allowed)" : "FALSE (Denied)") . "\n";
    
    if ($canCheck && !$hasInRole) {
        if ($hasDirect) {
            echo "WARNING: Check is TRUE because of DIRECT permission, even though ROLE is disabled.\n";
        } else {
            echo "WARNING: Check is TRUE but reason is UNKNOWN (possibly Super Admin status?).\n";
        }
    }
}
