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

app(TenantContext::class)->setCompanyId($companyId);
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
\Illuminate\Support\Facades\Cache::forget("user_{$user->id}_is_global_super_admin");

echo "Verifying زین (Zain) before revocation:\n";
echo "User can('Create Branch'): " . ($user->can('Create Branch') ? "YES" : "NO") . "\n";

echo "\nRevoking 'Create Branch' for Role 3 in Context $companyId...\n";
$role = Role::find(3);
setPermissionsTeamId($companyId);
$role->revokePermissionTo('Create Branch');

// Clear caches
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
$user->unsetRelation('roles');
$user->unsetRelation('permissions');

echo "\nVerifying زین (Zain) AFTER revocation:\n";
echo "User can('Create Branch'): " . ($user->can('Create Branch') ? "YES" : "NO") . "\n";

// Check if Role 3 still sees it in its relationship (testing the Role.php fix)
$rolePermissions = $role->permissions()->pluck('name')->toArray();
echo "\nRole 3 Permissions in current context (scoping test):\n";
echo "Has 'Create Branch'? " . (in_array('Create Branch', $rolePermissions) ? "YES" : "NO") . "\n";
