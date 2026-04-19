<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

$companyId = 1; // Hexafume
app(PermissionRegistrar::class)->setPermissionsTeamId($companyId);
echo "Current Team ID: " . app(PermissionRegistrar::class)->getPermissionsTeamId() . "\n";

// Check if Role "User" exists in Company 1
$roleInContext = Role::where('name', 'User')->where('company_id', $companyId)->first();
echo "Role 'User' in Context 1: " . ($roleInContext ? "ID {$roleInContext->id}" : "NOT FOUND") . "\n";

// Check global role
$globalRole = Role::where('name', 'User')->whereNull('company_id')->first();
echo "Global Role 'User': " . ($globalRole ? "ID {$globalRole->id}" : "NOT FOUND") . "\n";

echo "\n--- SIMULATING ASSIGNMENT ---\n";
$tempUser = User::factory()->create(['company_id' => $companyId]);
echo "Temp User Created: {$tempUser->email} (ID: {$tempUser->id})\n";

// This is the line from UserCreate.php
$tempUser->assignRole('User');

$assignedRoles = $tempUser->roles()->get();
echo "Assigned Roles for Temp User:\n";
foreach ($assignedRoles as $role) {
    echo " - Name: {$role->name} (ID: {$role->id})\n";
    echo "   - Company ID: " . ($role->company_id ?? 'Global') . "\n";
}

$tempUser->delete();
echo "\nTemp User Deleted.\n";
