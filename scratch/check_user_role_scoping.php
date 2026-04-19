<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- MODEL HAS ROLES CONTEXT AUDIT ---\n";

$rows = DB::table('model_has_roles')
    ->get();

if ($rows->isEmpty()) {
    echo "NO RECORDS FOUND IN model_has_roles.\n";
} else {
    foreach ($rows as $row) {
        $user = DB::table('users')->where('id', $row->model_id)->first();
        $role = DB::table('roles')->where('id', $row->role_id)->first();
        echo "User: '{$user->email}' (ID: {$row->model_id}) | Role: '{$role->name}' (ID: {$row->role_id}) | Company (Pivot): " . ($row->company_id ?? 'NULL') . "\n";
    }
}
