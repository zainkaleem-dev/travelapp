<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('user:make-super-admin {email}', function () {
    $email = (string) $this->argument('email');

    $user = User::query()->where('email', $email)->first();
    if (!$user) {
        $this->error('User not found: '.$email);
        return 1;
    }

    $user->forceFill([
        'is_super_admin' => true,
        'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
    ])->save();
    $this->info('Super admin enabled for: '.$email);

    return 0;
})->purpose('Mark an existing user as super admin');

Artisan::command('user:create-super-admin {email} {--password=} {--name=}', function () {
    $email = (string) $this->argument('email');
    $password = (string) ($this->option('password') ?: Str::password(16));
    $name = (string) ($this->option('name') ?: 'Super Admin');

    $existing = User::query()->where('email', $email)->first();
    if ($existing) {
        $this->error('User already exists: '.$email);
        return 1;
    }

    User::query()->create([
        'first_name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'is_super_admin' => true,
        'email_verified_at' => Carbon::now(),
    ]);

    $this->info('Super admin created.');
    $this->line('Email: '.$email);
    $this->line('Password: '.$password);

    return 0;
})->purpose('Create a new super admin user');
