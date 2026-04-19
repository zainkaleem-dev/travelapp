<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- FINAL BOSS VERIFY ---\n";

$roleId = 8;
$permissionId = 6;

$links = DB::table('role_has_permissions')
    ->where('role_id', $roleId)
    ->get();

echo "Role 8 has " . $links->count() . " permission links.\n";
foreach ($links as $l) {
    $p = DB::table('permissions')->where('id', $l->permission_id)->first();
    echo "  - Permission: '{$p->name}' (ID: {$l->permission_id}) | Company: " . ($l->company_id ?? 'NULL') . "\n";
}
