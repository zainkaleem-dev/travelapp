<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- DEFINITIVE DUMP (Permission 6: Create Branch) ---\n";

$rows = DB::table('role_has_permissions')
    ->where('permission_id', 6)
    ->get();

if ($rows->isEmpty()) {
    echo "NO ROLES HAVE PERMISSION 6.\n";
} else {
    foreach ($rows as $row) {
        $role = DB::table('roles')->where('id', $row->role_id)->first();
        $roleName = $role ? $role->name : "UNKNOWN";
        $roleCompany = $role ? ($role->company_id ?? 'NULL') : "N/A";
        echo "Role: '{$roleName}' (ID: {$row->role_id}) | Company (Role): {$roleCompany} | Company (Pivot): " . ($row->company_id ?? 'NULL') . "\n";
    }
}
