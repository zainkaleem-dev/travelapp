<?php
// C:\wamp64\www\travelapp\scratch\test_bypass.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

$admin = User::where('email', 'admin@gmail.com')->first();
if (!$admin) {
    die("Admin user not found." . PHP_EOL);
}

// Case 1: NO Team ID set
setPermissionsTeamId(null);
echo "Global Context (Team NULL):" . PHP_EOL;
echo "  - is Super Admin: " . ($admin->hasRole('super_admin') ? 'YES' : 'NO') . PHP_EOL;
echo "  - Gate allows 'anything': " . (Gate::forUser($admin)->allows('anything') ? 'YES' : 'NO') . PHP_EOL;

// Case 2: Specific Team ID set (simulating a company context)
setPermissionsTeamId(1);
echo PHP_EOL . "Company Context (Team 1):" . PHP_EOL;
echo "  - is Super Admin (standard check): " . ($admin->hasRole('super_admin') ? 'YES' : 'NO') . PHP_EOL;
echo "  - Gate allows 'anything' (with bypass): " . (Gate::forUser($admin)->allows('anything') ? 'YES' : 'NO') . PHP_EOL;

if (Gate::forUser($admin)->allows('anything')) {
    echo PHP_EOL . "SUCCESS: Global bypass is working correctly!" . PHP_EOL;
} else {
    echo PHP_EOL . "FAILURE: Global bypass is NOT working." . PHP_EOL;
}
