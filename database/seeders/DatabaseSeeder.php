<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            AdminSeeder::class,
            FeaturesSeeder::class,
            CountrySeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            OrganizationSeeder::class,
            TmcSeeder::class,
            MailTemplateSeeder::class,
        ]);
    }
}
