<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "--- ROLE ASSIGNMENT AUDIT (Cache Cleared) ---\n";

app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

$users = User::all();

foreach ($users as $u) {
    $roles = $u->roles;
    if ($roles->isEmpty()) continue;

    echo "User: {$u->email} (ID: {$u->id})\n";
    
    foreach ($roles as $r) {
        echo "  Role: {$r->name} (ID: {$r->id}, Company: {$r->company_id})\n";
        
        $hasInRole = DB::table('role_has_permissions')
            ->where('role_id', $r->id)
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('permissions.name', 'Create Branch')
            ->exists();
            
        echo "    -> Role has 'Create Branch': " . ($hasInRole ? "YES" : "NO") . "\n";
    }

    // Direct check per company context found in roles
    $processedCompanies = [];
    foreach ($roles as $r) {
        $cId = $r->company_id ?: 0;
        if (in_array($cId, $processedCompanies)) continue;
        $processedCompanies[] = $cId;
        
        echo "  Context Company $cId:\n";
        
        $hasDirect = DB::table('model_has_permissions')
            ->where('model_id', $u->id)
            ->where('model_type', User::class)
            ->where('company_id', $cId)
            ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('permissions.name', 'Create Branch')
            ->exists();
            
        echo "    -> Direct 'Create Branch': " . ($hasDirect ? "YES" : "NO") . "\n";
        
        setPermissionsTeamId($cId);
        $canCreateBranch = $u->can('Create Branch');
        $hasManageGlobal = $u->hasPermissionTo('Manage Global System');
        
        echo "    -> user->can('Create Branch'): " . ($canCreateBranch ? "TRUE (ALLOWED)" : "FALSE (DENIED)") . "\n";
        echo "    -> user->hasPermissionTo('Manage Global System'): " . ($hasManageGlobal ? "YES" : "NO") . "\n";
    }
    echo "---------------------------------\n";
}
