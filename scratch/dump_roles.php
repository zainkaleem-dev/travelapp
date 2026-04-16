<?php
// C:\wamp64\www\travelapp\scratch\dump_roles.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$roles = DB::table('roles')->get();
echo "Roles in database (detailed):" . PHP_EOL;
foreach ($roles as $role) {
    echo "ID: {$role->id} | Name: {$role->name} | Company ID: " . ($role->company_id ?? 'NULL') . PHP_EOL;
}
