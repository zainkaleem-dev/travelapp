<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$email = 'zain@gmial.com';
$u = User::where('email', $email)->first();
$permission = 'Create Branch';

echo "--- SPATIE RESOLUTION AUDIT ---\n";

setPermissionsTeamId(1);
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "Direct Permissions:\n";
foreach ($u->getDirectPermissions() as $p) {
    echo "  - {$p->name} (ID: {$p->id})\n";
}

echo "Permissions via Roles:\n";
foreach ($u->getPermissionsViaRoles() as $p) {
    echo "  - {$p->name} (ID: {$p->id})\n";
}

echo "All Permissions:\n";
foreach ($u->getAllPermissions() as $p) {
    echo "  - {$p->name} (ID: {$p->id})\n";
}

echo "Checking the specific role that might have it:\n";
foreach ($u->roles as $r) {
    $has = $r->hasPermissionTo($permission);
    echo "  Role: {$r->name} (ID: {$r->id}) -> hasPermissionTo('$permission'): " . ($has ? "YES" : "NO") . "\n";
}
