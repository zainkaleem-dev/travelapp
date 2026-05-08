<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Role;
use Spatie\Permission\Models\Permission;

echo "--- ISOLATION VERIFICATION ---\n";

// 1. Get two roles in the same company
$admin = Role::where('name', 'Partner Admin')->whereNotNull('company_id')->first();
$agent = Role::where('name', 'Agent')->where('company_id', $admin->company_id)->first();

if (!$admin || !$agent) {
    die("Roles not found for test.\n");
}

echo "Testing Company ID: {$admin->company_id}\n";
echo "Admin Role ID: {$admin->id}\n";
echo "Agent Role ID: {$agent->id}\n";

// 2. Grant permission to Agent only
$permName = 'View Branch';
$agent->permissions()->syncWithoutDetaching([
    Permission::findByName($permName)->id => ['company_id' => $admin->company_id]
]);

// 3. Clear cache
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

// 4. Check if Admin has it (Should be NO)
$adminHasIt = $admin->hasPermissionTo($permName);
$agentHasIt = $agent->hasPermissionTo($permName);

echo "Agent has '{$permName}': " . ($agentHasIt ? 'YES' : 'NO') . "\n";
echo "Admin has '{$permName}': " . ($adminHasIt ? 'YES' : 'NO') . "\n";

if ($agentHasIt && !$adminHasIt) {
    echo "SUCCESS: Roles are isolated within the same company!\n";
} else {
    echo "FAILURE: Permissions are bleeding!\n";
}

echo "--- END VERIFICATION ---\n";
