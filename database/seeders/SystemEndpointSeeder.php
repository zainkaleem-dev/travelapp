<?php

namespace Database\Seeders;

use App\Models\SystemEndpoint;
use App\Models\Company;
use Illuminate\Database\Seeder;

class SystemEndpointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::limit(5)->get();

        if ($companies->isEmpty()) {
            return;
        }

        foreach ($companies as $company) {
            SystemEndpoint::create([
                'company_id' => $company->id,
                'endpoint_name' => 'Flight Search API',
                'endpoint_link' => 'https://api.example.com/v1/flights/search',
                'description' => 'Retrieves available flight options based on search criteria.',
                'is_verified' => true,
            ]);

            SystemEndpoint::create([
                'company_id' => $company->id,
                'endpoint_name' => 'Booking Confirmation API',
                'endpoint_link' => 'https://api.example.com/v1/bookings/confirm',
                'description' => 'Confirms a flight booking and generates a PNR.',
                'is_verified' => false,
            ]);
        }
    }
}
