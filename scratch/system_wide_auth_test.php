<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

$email = 'blank_test@gmail.com';
$u = User::where('email', $email)->first();
if ($u) $u->delete();

$u = User::create([
    'first_name' => 'Blank',
    'last_name' => 'User',
    'email' => $email,
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
    'company_id' => 1,
]);

echo "--- SYSTEM WIDE AUTH TEST: $email ---\n";

setPermissionsTeamId(1);

echo "Checking 'Create Branch':\n";
echo "  - Spatie hasPermissionTo: " . ($u->hasPermissionTo('Create Branch') ? "TRUE" : "FALSE") . "\n";
echo "  - Gate allows: " . (Gate::forUser($u)->allows('Create Branch') ? "TRUE" : "FALSE") . "\n";

echo "Checking 'Manage Global System':\n";
echo "  - Spatie hasPermissionTo: " . ($u->hasPermissionTo('Manage Global System') ? "TRUE" : "FALSE") . "\n";

$u->delete();
