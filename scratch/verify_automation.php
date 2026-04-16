<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Company;
use App\Models\Role;

echo "Creating test company...\n";
$company = Company::create(['name' => 'Automated Test Agency ' . time()]);

echo "Checking roles for Company ID: {$company->id}...\n";
$roles = Role::where('company_id', $company->id)->get();

if ($roles->count() === 4) {
    echo "SUCCESS: Found 4 default roles for the company.\n";
    foreach ($roles as $role) {
        $perms = $role->permissions()->count();
        echo " - Role: {$role->name} (Permissions: {$perms})\n";
    }
} else {
    echo "FAILURE: Found " . $roles->count() . " roles instead of 4.\n";
}

$company->delete(); // Cleanup
echo "Cleanup done.\n";
