<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$company = App\Models\Company::query()->latest('id')->first();

if (!$company) {
    echo "No companies found.\n";
    exit(0);
}

echo "Company #{$company->id}: {$company->name}\n";
echo "logo_path: " . ($company->logo_path ?? 'NULL') . "\n";

if ($company->logo_path) {
    $storagePath = storage_path('app/public/' . $company->logo_path);
    echo "storage file exists: " . (is_file($storagePath) ? 'yes' : 'no') . "\n";
    echo "storage full path: {$storagePath}\n";
    echo "public url: " . Illuminate\Support\Facades\Storage::disk('public')->url($company->logo_path) . "\n";
}

