<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

echo "--- ALL PERMISSIONS AUDIT ---\n";
foreach (Permission::all() as $p) {
    echo "ID: {$p->id} | Name: '{$p->name}' | Guard: {$p->guard_name}\n";
}
