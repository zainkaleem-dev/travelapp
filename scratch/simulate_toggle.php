<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Support\TenantContext;

$user = User::where('email', 'zain@gmail.com')->first();
$companyId = 1;
$role = Role::find(3); // Organization Admin (Context 1)

app(TenantContext::class)->setCompanyId($companyId);
setPermissionsTeamId($companyId);
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "Initial State for Zain (Company 1):\n";
echo "User can('Create Branch'): " . ($user->can('Create Branch') ? "YES" : "NO") . "\n";

echo "\n--- SIMULATING TOGGLE OFF ---\n";
// This matches RolesPermissions.php logic
if ($role->hasPermissionTo('Create Branch')) {
    echo "Role has permission. Revoking...\n";
    $role->revokePermissionTo('Create Branch');
} else {
    echo "Role does not have permission according to hasPermissionTo(). (THIS IS THE BUG IF IT WAS CHECKED)\n";
}

// Refresh state for the SAME request
$role->unsetRelation('permissions');
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "\nPost-Toggle State:\n";
echo "Role->hasPermissionTo('Create Branch'): " . ($role->hasPermissionTo('Create Branch') ? "YES" : "NO") . "\n";
echo "User can('Create Branch'): " . ($user->can('Create Branch') ? "YES" : "NO") . "\n";

// Raw DB check
$exists = \Illuminate\Support\Facades\DB::table('role_has_permissions')
    ->where('role_id', $role->id)
    ->where('permission_id', \App\Models\Permission::findByName('Create Branch')->id)
    ->where('company_id', $companyId)
    ->exists();
echo "Raw DB Record exists for (Role 3, Context 1): " . ($exists ? "YES" : "NO") . "\n";
