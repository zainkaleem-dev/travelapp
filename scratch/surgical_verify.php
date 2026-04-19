<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- SURGICAL VERIFY ---\n";

$roleId = 8;
$permissionId = 6;

$exists = DB::table('role_has_permissions')
    ->where('role_id', $roleId)
    ->where('permission_id', $permissionId)
    ->exists();

echo "Role ID $roleId has Permission ID $permissionId in role_has_permissions: " . ($exists ? "YES" : "NO") . "\n";

// Check for ANY permission with that ID linked to this role
$allPerms = DB::table('role_has_permissions')
    ->where('role_id', $roleId)
    ->pluck('permission_id')
    ->toArray();

echo "Role ID $roleId ALL permission IDs: " . implode(', ', $allPerms) . "\n";

// Check what ID 6 is again
$p = DB::table('permissions')->where('id', 6)->first();
echo "Permission ID 6 is '{$p->name}' (Guard: {$p->guard_name})\n";
