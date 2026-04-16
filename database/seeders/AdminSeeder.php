<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin user.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'User',
                'password' => 'admin123',
                'email_verified_at' => Carbon::now(),
            ]
        );
        $admin->save();

        // Ensure we are in a global context (no company) before assigning super admin
        setPermissionsTeamId(null);
        $admin->assignRole('Super Admin');
    }
}
