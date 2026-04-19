<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- SURGICAL ROLE AUDIT ---\n";

$allRoles = DB::table('model_has_roles')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->join('users', 'model_has_roles.model_id', '=', 'users.id')
    ->select('users.email', 'roles.name', 'model_has_roles.company_id', 'model_has_roles.role_id')
    ->get();

foreach ($allRoles as $r) {
    echo "User: {$r->email} | Role: '{$r->name}' (ID: {$r->role_id}) | Company: " . ($r->company_id ?? 'NULL') . "\n";
}

if ($allRoles->isEmpty()) {
    echo "No entries in model_has_roles.\n";
}
