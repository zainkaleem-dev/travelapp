<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

echo "--- BLADE @CAN SIMULATION FOR ZAIN ---\n";

$zain = User::where('email', 'zain@gmial.com')->first();
if (!$zain) {
    echo "ZAIN NOT FOUND.\n";
    exit;
}

// Act as Zain
auth()->login($zain);

echo "Checking permissions for User: '{$zain->email}' (ID: {$zain->id})\n";
echo "Company ID: " . ($zain->company_id ?? 'NULL') . "\n";

$abilities = ['Create Branch', 'Edit Branch', 'View Branch'];

foreach ($abilities as $ability) {
    $result = Gate::allows($ability) ? 'ALLOWED' : 'DENIED';
    echo "  - Gate::allows('{$ability}'): {$result}\n";
    
    $check = $zain->hasPermissionTo($ability) ? 'HAS' : 'NO';
    echo "    - \$user->hasPermissionTo('{$ability}'): {$check}\n";
}

// Check Super Status
echo "  - \$user->isSuperAdmin(): " . ($zain->isSuperAdmin() ? 'TRUE' : 'FALSE') . "\n";

// Check the Gate::before logic (emulated)
$isGlobalSuperAdmin = \Illuminate\Support\Facades\DB::table('model_has_roles')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->where('model_has_roles.model_id', $zain->id)
    ->where('roles.name', 'Super Admin')
    ->whereNull('model_has_roles.company_id')
    ->exists();
echo "  - Global Super Admin Logic Check: " . ($isGlobalSuperAdmin ? 'TRUE' : 'FALSE') . "\n";
