<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "--- ROLE ASSIGNMENT AUDIT ---\n";
$users = User::all();

foreach ($users as $user) {
    echo "User: {$user->email} (Company ID: " . ($user->company_id ?? 'Global') . ")\n";
    $assignments = DB::table('model_has_roles')
        ->where('model_id', $user->id)
        ->where('model_type', User::class)
        ->get();

    if ($assignments->isEmpty()) {
        echo " - No roles assigned.\n";
    } else {
        foreach ($assignments as $asgn) {
            $role = DB::table('roles')->where('id', $asgn->role_id)->first();
            $roleName = $role ? $role->name : 'Unknown';
            $roleCtx = $role ? ($role->company_id ?? 'Global') : 'N/A';
            
            echo " - Role: {$roleName} (ID: {$asgn->role_id})\n";
            echo "   - Assigned in Context (company_id): " . ($asgn->company_id ?? 'Global') . "\n";
            echo "   - Role itself belongs to (company_id): {$roleCtx}\n";
        }
    }
    echo "---------------------------\n";
}
