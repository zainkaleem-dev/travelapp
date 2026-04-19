<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

$email = 'zain@gmial.com';
$u = User::where('email', $email)->first();
$permission = 'Create Branch';

echo "--- FINAL DEEP TRACE: $email -> $permission ---\n";

$companyContexts = [null, 0, 1];

foreach ($companyContexts as $cid) {
    setPermissionsTeamId($cid);
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    echo "Context Company: " . ($cid ?? 'NULL') . "\n";
    echo "  - hasPermissionTo('Manage Global System'): " . ($u->hasPermissionTo('Manage Global System') ? "YES" : "NO") . "\n";
    echo "  - hasPermissionTo('Create Branch'): " . ($u->hasPermissionTo('Create Branch') ? "YES" : "NO") . "\n";
    
    $roles = $u->roles;
    echo "  - Roles in this context: " . $roles->pluck('name')->implode(', ') . "\n";
    foreach ($roles as $r) {
        $hasP = $r->hasPermissionTo('Create Branch');
        echo "    * Role '{$r->name}' (ID: {$r->id}) has 'Create Branch': " . ($hasP ? "YES" : "NO") . "\n";
    }
}

echo "Gate check (Current Context 1):\n";
setPermissionsTeamId(1);
echo "  - Gate::allows('Create Branch'): " . (Gate::forUser($u)->allows('Create Branch') ? "TRUE" : "FALSE") . "\n";
echo "  - Gate::allows('NON_EXISTENT'): " . (Gate::forUser($u)->allows('NON_EXISTENT') ? "TRUE" : "FALSE") . "\n";

