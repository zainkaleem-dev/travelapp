<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$permsToCheck = [5 => 'View Branch', 6 => 'Create Branch', 7 => 'Edit Branch'];

foreach ($permsToCheck as $id => $name) {
    echo "--- Mapping for {$name} (ID {$id}) ---\n";
    $rows = DB::table('role_has_permissions')->where('permission_id', $id)->get();
    if ($rows->isEmpty()) {
        echo "  NO ROLES HAVE THIS PERMISSION.\n";
    } else {
        foreach ($rows as $row) {
            $role = DB::table('roles')->where('id', $row->role_id)->first();
            echo "  Role: '{$role->name}' (ID: {$row->role_id}) | Company: " . ($role->company_id ?? 'NULL') . "\n";
        }
    }
}
