<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$emails = ['zain@gmail.com', 'zain@gmial.com'];

foreach ($emails as $email) {
    echo "--- AUDIT FOR: {$email} ---\n";
    $user = User::where('email', $email)->first();
    if (!$user) {
        echo "  USER NOT FOUND.\n";
        continue;
    }

    echo "  User ID: {$user->id} | Company ID: " . ($user->company_id ?? 'NULL') . "\n";
    echo "  Roles:\n";
    $roles = DB::table('model_has_roles')
        ->where('model_id', $user->id)
        ->get();
    foreach ($roles as $row) {
        $role = DB::table('roles')->where('id', $row->role_id)->first();
        echo "    - '{$role->name}' (ID: {$row->role_id}) | Pivot Company: " . ($row->company_id ?? 'NULL') . "\n";
    }

    echo "  Permission 'Create Branch' (ID 6) check:\n";
    echo "    - hasPermissionTo('Create Branch'): " . ($user->hasPermissionTo('Create Branch') ? 'TRUE' : 'FALSE') . "\n";
    
    echo "  Direct Permissions via Pivot (Permission 6):\n";
    $mhp = DB::table('model_has_permissions')
        ->where('model_id', $user->id)
        ->where('permission_id', 6)
        ->get();
    foreach ($mhp as $row) {
        echo "    - ID 6 | Company ID: " . ($row->company_id ?? 'NULL') . "\n";
    }
}
