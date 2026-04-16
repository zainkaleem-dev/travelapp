<?php
// C:\wamp64\www\travelapp\scratch\db_cleanup.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Starting Database Index Cleanup..." . PHP_EOL;

try {
    Schema::table('roles', function (Blueprint $table) {
        $table->dropUnique('roles_company_id_name_guard_name_unique');
        echo "✅ Dropped redundant index 'roles_company_id_name_guard_name_unique'" . PHP_EOL;
    });
} catch (\Exception $e) {
    echo "⚠️ Index 'roles_company_id_name_guard_name_unique' already missing or could not be dropped: " . $e->getMessage() . PHP_EOL;
}

echo "Cleanup Complete." . PHP_EOL;
