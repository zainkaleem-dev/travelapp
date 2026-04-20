<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$user = User::where('email', 'zain@gmail.com')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User: {$user->email} (ID: {$user->id})\n";
echo "Active Company ID: {$user->company_id}\n";

// Check Gate before bypass
$hasGlobalBypass = DB::table('model_has_permissions')
    ->where('model_id', $user->id)
    ->where('model_type', User::class)
    ->where('permission_id', DB::table('permissions')->where('name', 'Manage Global System')->value('id'))
    ->exists();

$roleBypass = DB::table('model_has_roles')
    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
    ->where('model_has_roles.model_id', $user->id)
    ->where('role_has_permissions.permission_id', DB::table('permissions')->where('name', 'Manage Global System')->value('id'))
    ->exists();

echo "Global Bypass (Permission): " . ($hasGlobalBypass ? 'YES' : 'NO') . "\n";
echo "Global Bypass (Role): " . ($roleBypass ? 'YES' : 'NO') . "\n";

echo "\nChecking 'Create Branch' for context 1:\n";
setPermissionsTeamId(1);
echo "User can('Create Branch'): " . ($user->can('Create Branch') ? 'YES' : 'NO') . "\n";

$roleWithPermission = DB::table('model_has_roles')
    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
    ->where('model_has_roles.model_id', $user->id)
    ->where('role_has_permissions.permission_id', DB::table('permissions')->where('name', 'Create Branch')->value('id'))
    ->where('role_has_permissions.company_id', 1)
    ->exists();

echo "DB has Role with 'Create Branch' for Context 1: " . ($roleWithPermission ? 'YES' : 'NO') . "\n";
