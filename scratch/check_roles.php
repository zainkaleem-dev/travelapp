<?php
// C:\wamp64\www\travelapp\scratch\check_roles.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Current Team ID: " . getPermissionsTeamId() . PHP_EOL;

$users = User::all();
foreach ($users as $user) {
    echo "User: {$user->email} (Company ID: " . ($user->company_id ?? 'NULL') . ")" . PHP_EOL;
    
    $roles = DB::table('model_has_roles')
        ->where('model_id', $user->id)
        ->get();
        
    foreach ($roles as $role) {
        $roleName = DB::table('roles')->where('id', $role->role_id)->value('name');
        echo "  - Role: {$roleName} (Team/Company ID: " . ($role->company_id ?? 'GLOBAL/NULL') . ")" . PHP_EOL;
    }
}
