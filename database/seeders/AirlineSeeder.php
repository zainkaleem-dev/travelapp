<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/flightsearch/airlines.json');
        if (!is_file($path)) {
            return;
        }

        $airlines = json_decode(file_get_contents($path), true);
        if (!is_array($airlines)) {
            return;
        }

        foreach ($airlines as $airline) {
            \App\Models\Airline::updateOrCreate(
                ['code' => $airline['code']],
                ['name' => $airline['name']]
            );
        }
    }
}
