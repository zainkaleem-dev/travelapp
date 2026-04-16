<?php
// C:\wamp64\www\travelapp\scratch\check_indexes.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$indexes = Schema::getIndexes('roles');
echo "Indexes on 'roles' table:" . PHP_EOL;
foreach ($indexes as $index) {
    echo "Name: {$index['name']} | Columns: " . implode(', ', $index['columns']) . " | Unique: " . ($index['unique'] ? 'YES' : 'NO') . PHP_EOL;
}
