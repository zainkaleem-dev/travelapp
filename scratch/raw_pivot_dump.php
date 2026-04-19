<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- RAW PIVOT DUMP (role_has_permissions) ---\n";

$rows = DB::table('role_has_permissions')->get();
echo "Found " . $rows->count() . " rows.\n";

foreach ($rows as $row) {
    echo "Role: {$row->role_id} | Permission: {$row->permission_id} | Company: " . ($row->company_id ?? 'NULL') . "\n";
}
