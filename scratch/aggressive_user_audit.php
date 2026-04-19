<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

$email = 'zain@gmial.com';
$u = User::where('email', $email)->first();

if (!$u) { die("User $email not found.\n"); }

echo "--- AGGRESSIVE AUDIT: $email (ID: {$u->id}) ---\n";

// 1. RAW model_has_roles
$rawRoles = DB::table('model_has_roles')->where('model_id', $u->id)->get();
echo "RAW model_has_roles:\n";
foreach ($rawRoles as $rr) {
    echo "  - RoleID: {$rr->role_id} | CompanyID: " . ($rr->company_id ?? 'NULL') . "\n";
}

// 2. RAW model_has_permissions
$rawPerms = DB::table('model_has_permissions')->where('model_id', $u->id)->get();
echo "RAW model_has_permissions:\n";
foreach ($rawPerms as $rp) {
    echo "  - PermissionID: {$rp->permission_id} | CompanyID: " . ($rp->company_id ?? 'NULL') . "\n";
}

// 3. Spatie Logic Check
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "Spatie can() checks:\n";
$companiesTotest = [0, 1];
foreach ($companiesTotest as $cid) {
    setPermissionsTeamId($cid ?: null);
    echo "  Company Context $cid:\n";
    echo "    - can('Create Branch'): " . ($u->can('Create Branch') ? "TRUE" : "FALSE") . "\n";
    echo "    - hasPermissionTo('Manage Global System'): " . ($u->hasPermissionTo('Manage Global System') ? "TRUE" : "FALSE") . "\n";
}

echo "Gate Check (Static):\n";
echo "  - Gate::allows('Create Branch') with user $email: " . (Illuminate\Support\Facades\Gate::forUser($u)->allows('Create Branch') ? "TRUE" : "FALSE") . "\n";
