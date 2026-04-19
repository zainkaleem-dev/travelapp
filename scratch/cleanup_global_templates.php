<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- CLEANUP GLOBAL TEMPLATES (Permission 6) ---\n";

$deleted = DB::table('role_has_permissions')
    ->where('permission_id', 6)
    ->whereNull('company_id')
    ->delete();

echo "Deleted {$deleted} global mappings for Permission 6.\n";

// Also clear the Spatie cache again
\Spatie\Permission\PermissionRegistrar::class;
app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
echo "Spatie cache cleared.\n";
