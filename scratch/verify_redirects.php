<?php
// C:\wamp64\www\travelapp\scratch\verify_redirects.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

function checkRedirectFor($email) {
    $user = User::where('email', $email)->first();
    if (!$user) {
        echo "❌ User {$email} not found!" . PHP_EOL;
        return;
    }

    Auth::login($user);
    
    // Simulate the logic in Login.blade.php / root route
    $target = '';
    if ($user->hasRole('super_admin')) {
        $target = route('superadmin.companies.index');
    } elseif ($user->hasRole('company_admin')) {
        $target = route('company.companies.index');
    } else {
        $target = route('flights.search');
    }

    echo "✅ User: {$email} (Roles: " . $user->roles->pluck('name')->implode(', ') . ")" . PHP_EOL;
    echo "   Target Redirect: {$target}" . PHP_EOL . PHP_EOL;
}

echo "--- LOGIN REDIRECTION VERIFICATION ---" . PHP_EOL . PHP_EOL;

checkRedirectFor('admin@gmail.com');

// Create a temporary company admin for testing
$cAdmin = User::factory()->create(['company_id' => 1]);
$cAdmin->assignRole('company_admin');
checkRedirectFor($cAdmin->email);
$cAdmin->delete();

// Normal user
$u = User::factory()->create();
checkRedirectFor($u->email);
$u->delete();

echo "Verification Complete." . PHP_EOL;
