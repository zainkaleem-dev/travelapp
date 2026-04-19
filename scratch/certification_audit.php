<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- SECURITY CERTIFICATION AUDIT ---\n";

$discrepancies = [];

// 1. Audit role_has_permissions
echo "Auditing role_has_permissions...\n";
$rhp = DB::table('role_has_permissions')->get();
foreach ($rhp as $row) {
    $role = DB::table('roles')->where('id', $row->role_id)->first();
    if (!$role) continue;
    
    if ($role->company_id !== $row->company_id) {
        $discrepancies[] = "MISMATCH: Role ID {$role->id} ('{$role->name}') has permission ID {$row->permission_id} with Company ID " . ($row->company_id ?? 'NULL') . " but Role belongs to Company " . ($role->company_id ?? 'NULL');
    }
}

// 2. Audit model_has_roles
echo "Auditing model_has_roles...\n";
$mhr = DB::table('model_has_roles')->get();
foreach ($mhr as $row) {
    $user = DB::table('users')->where('id', $row->model_id)->first();
    if (!$user) continue;
    
    if ($user->company_id !== $row->company_id) {
         // Special case: Super Admins can have NULL global roles
         $role = DB::table('roles')->where('id', $row->role_id)->first();
         if ($role->name === 'Super Admin' && $user->company_id === null && $row->company_id === null) {
             continue; 
         }
         
         $discrepancies[] = "MISMATCH: User ID {$user->id} ('{$user->email}') assigned Role ID {$row->role_id} with Company ID " . ($row->company_id ?? 'NULL') . " but User belongs to Company " . ($user->company_id ?? 'NULL');
    }
}

if (empty($discrepancies)) {
    echo "NO DISCREPANCIES FOUND. Your database is perfectly synchronized based on company_id.\n";
} else {
    echo "DISCREPANCIES FOUND:\n";
    foreach ($discrepancies as $d) echo "  - {$d}\n";
}
