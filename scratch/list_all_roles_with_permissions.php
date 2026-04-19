<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "--- ALL ROLES PERMISSION AUDIT ---\n";
foreach (Role::all() as $r) {
    $perms = DB::table('role_has_permissions')
        ->where('role_id', $r->id)
        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->pluck('permissions.name')
        ->toArray();
    echo "ID: {$r->id} | Name: '{$r->name}' | Company: " . ($r->company_id ?? 'NULL') . " | Status: {$r->status}\n";
    echo "  Permissions: " . implode(', ', $perms) . "\n";
}
