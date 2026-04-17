<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "Checking for setPermissionsTeamId function...\n";
if (function_exists('setPermissionsTeamId')) {
    echo "Found! It is defined.\n";
} else {
    echo "NOT FOUND! Global function does not exist.\n";
}

echo "Checking for getPermissionsTeamId function...\n";
if (function_exists('getPermissionsTeamId')) {
    echo "Found! It is defined.\n";
} else {
    echo "NOT FOUND! Global function does not exist.\n";
}
