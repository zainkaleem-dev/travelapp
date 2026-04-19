<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- REFINED GLOBAL AUDIT (Permission 6) ---\n";

$rows = DB::table('role_has_permissions')
    ->where('permission_id', 6)
    ->get();

foreach ($rows as $row) {
    $role = DB::table('roles')->where('id', $row->role_id)->first();
    echo "Role: '{$role->name}' (ID: {$row->role_id}) | Company: " . ($row->company_id ?? 'NULL') . "\n";
}
