<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

$email = 'repro_test@gmail.com';
$u = User::where('email', $email)->first();
if ($u) $u->delete();

$u = User::create([
    'first_name' => 'Repro',
    'last_name' => 'Test',
    'email' => $email,
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
    'company_id' => 1,
]);

echo "--- SURGICAL REPRODUCTION TEST: $email ---\n";

setPermissionsTeamId(1);
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

// Assign Role 8 (Organization Admin)
$role = \App\Models\Role::find(8);
$u->assignRole($role);

echo "User created and assigned Role ID: {$role->id} ({$role->name})\n";

echo "Final Checks for $email:\n";
echo "  - Spatie hasPermissionTo('Create Branch'): " . ($u->hasPermissionTo('Create Branch') ? "TRUE" : "FALSE") . "\n";
echo "  - Gate allows('Create Branch'): " . (Gate::forUser($u)->allows('Create Branch') ? "TRUE" : "FALSE") . "\n";

$u->delete();
