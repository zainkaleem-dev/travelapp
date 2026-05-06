<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/flightsearch/locations.json');
        if (!is_file($path)) {
            $this->command->error("locations.json not found!");
            return;
        }

        $data = json_decode(file_get_contents($path), true);
        if (!isset($data['airports']) || !is_array($data['airports'])) {
            $this->command->error("Invalid locations.json format!");
            return;
        }

        $aliases = [
            'UNITED STATES OF AMERICA' => 'United States',
            'TURKIYE' => 'Turkey',
            'RUSSIAN FEDERATION' => 'Russia',
            'MACAU SAR' => 'Macau',
            'HONG KONG SAR' => 'Hong Kong',
            'VIET NAM' => 'Vietnam',
            'LAO PEOPLES DEMOCRATIC REPUBLIC' => 'Laos',
            'DEMOCRATIC REPUBLIC OF CONGO' => 'Congo',
            'CZECHIA' => 'Czech Republic',
            'REPUBLIC OF KOREA' => 'South Korea',
            'DEMOCRATIC PEOPLES REPUBLIC OF KOREA' => 'North Korea',
        ];

        $countriesMap = Country::all()->mapWithKeys(function ($c) {
            return [strtoupper($c->name) => $c->id];
        })->toArray();

        // Default to US just in case it completely fails, or skip
        $defaultCountryId = $countriesMap['UNITED STATES'] ?? null;

        $count = 0;
        foreach ($data['airports'] as $ap) {
            $countryName = strtoupper($ap['country']);
            if (isset($aliases[$countryName])) {
                $countryName = strtoupper($aliases[$countryName]);
            }

            $countryId = $countriesMap[$countryName] ?? null;
            
            if (!$countryId) {
                // Try partial match
                $found = Country::where('name', 'LIKE', '%' . $ap['country'] . '%')->first();
                if ($found) {
                    $countryId = $found->id;
                    $countriesMap[$countryName] = $countryId;
                } else {
                    $countryId = $defaultCountryId; // fallback
                }
            }

            if (!$countryId) continue;

            $city = City::firstOrCreate([
                'name' => ucwords(strtolower($ap['city'])),
                'country_id' => $countryId
            ]);

            Airport::updateOrCreate(
                ['code' => $ap['code']],
                [
                    'city_id' => $city->id,
                    'name' => ucwords(strtolower($ap['airport']))
                ]
            );

            $count++;
            if ($count % 500 == 0) {
                $this->command->info("Seeded $count airports...");
            }
        }
        $this->command->info("Seeded all $count airports successfully.");
    }
}
