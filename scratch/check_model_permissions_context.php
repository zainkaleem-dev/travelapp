<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- MODEL HAS PERMISSIONS CONTEXT AUDIT ---\n";

$rows = DB::table('model_has_permissions')
    ->limit(20)
    ->get();

if ($rows->isEmpty()) {
    echo "NO RECORDS FOUND IN model_has_permissions.\n";
} else {
    foreach ($rows as $row) {
        $user = DB::table('users')->where('id', $row->model_id)->first();
        $perm = DB::table('permissions')->where('id', $row->permission_id)->first();
        echo "User: '{$user->email}' (ID: {$row->model_id}) | Permission: '{$perm->name}' | Company (Pivot): " . ($row->company_id ?? 'NULL') . "\n";
    }
}
