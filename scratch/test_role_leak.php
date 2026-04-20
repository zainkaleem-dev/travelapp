<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;

$role = Role::find(3); // Organization Admin (Company 1)

echo "Testing Role-Permission Context Visibility (Current Context: 1)\n";
setPermissionsTeamId(1);

// Currently Role 3 DOES NOT have Create Branch in Context 1 (I revoked it in previous script)
// But it HAS it in Context 2.

echo "Spatie Default hasPermissionTo('Create Branch'): " . ($role->hasPermissionTo('Create Branch') ? "YES" : "NO") . "\n";

echo "Manual Scoped Check: " . ($role->permissions()->where('name', 'Create Branch')->exists() ? "YES" : "NO") . "\n";
