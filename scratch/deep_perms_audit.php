<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$user = User::where('email', 'zain@gmail.com')->first();
$companyId = 1;

app(\App\Support\TenantContext::class)->setCompanyId($companyId);
setPermissionsTeamId($companyId);
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "User: {$user->email} (ID: {$user->id})\n";
echo "Context: $companyId\n";

echo "\n--- RAW DB CHECK (model_has_roles) ---\n";
$roles = DB::table('model_has_roles')
    ->where('model_id', $user->id)
    ->get();
foreach ($roles as $r) {
    $roleName = DB::table('roles')->where('id', $r->role_id)->value('name');
    echo "Role: $roleName (ID: {$r->role_id}) Context: " . ($r->company_id ?? 'NULL') . "\n";
    
    echo "  Permissions for this Role context:\n";
    $perms = DB::table('role_has_permissions')
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->where('role_id', $r->role_id)
        ->where('company_id', $r->company_id)
        ->get(['permissions.name']);
    foreach ($perms as $p) {
        echo "   - {$p->name}\n";
    }
}

echo "\n--- RAW DB CHECK (model_has_permissions) ---\n";
$directPerms = DB::table('model_has_permissions')
    ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
    ->where('model_id', $user->id)
    ->get(['permissions.name', 'model_has_permissions.company_id']);
foreach ($directPerms as $dp) {
    echo "Permission: {$dp->name} Context: " . ($dp->company_id ?? 'NULL') . "\n";
}

echo "\n--- FINAL AUTHORIZATION TEST ---\n";
echo "can('Create Branch'): " . ($user->can('Create Branch') ? 'YES' : 'NO') . "\n";
echo "hasPermissionTo('Create Branch'): " . ($user->hasPermissionTo('Create Branch') ? 'YES' : 'NO') . "\n";

echo "\n--- GATE DEBUG ---\n";
$gate = app(\Illuminate\Contracts\Auth\Access\Gate::class);
echo "Gate allows 'Create Branch': " . ($gate->forUser($user)->allows('Create Branch') ? 'YES' : 'NO') . "\n";
