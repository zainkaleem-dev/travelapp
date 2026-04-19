<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "--- FINAL ACCOUNT AUDIT ---\n";

$users = User::where('email', 'like', 'zain%')->get();
echo "Found " . $users->count() . " users matching 'zain%'.\n";

foreach ($users as $u) {
    echo "User: {$u->email} (ID: {$u->id})\n";
    $roles = DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_id', $u->id)
        ->select('roles.name', 'model_has_roles.company_id')
        ->get();
        
    foreach ($roles as $r) {
        echo "  - Role: '{$r->name}' | Company: " . ($r->company_id ?? 'NULL') . "\n";
    }
}
