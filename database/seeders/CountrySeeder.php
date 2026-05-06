<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = \App\Helpers\CountryHelper::getAllCountries();

        foreach ($countries as $country) {
            \App\Models\Country::updateOrCreate(
                ['code' => $country['code']],
                [
                    'name' => $country['name'],
                    'dial_code' => $country['dial_code'],
                ]
            );
        }
    }
}
