<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- GLOBAL PERMISSION LEAK AUDIT ---\n";

$directs = DB::table('model_has_permissions')
    ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
    ->join('users', 'model_has_permissions.model_id', '=', 'users.id')
    ->select('users.email', 'permissions.name', 'model_has_permissions.company_id', 'model_has_permissions.model_id')
    ->get();

foreach ($directs as $d) {
    echo "User: {$d->email} (ID: {$d->model_id}) | Permission: '{$d->name}' | Company: " . ($d->company_id ?? 'NULL') . "\n";
}

if ($directs->isEmpty()) {
    echo "No direct permissions found in model_has_permissions table.\n";
}
