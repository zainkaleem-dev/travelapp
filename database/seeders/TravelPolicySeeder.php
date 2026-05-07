<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = \App\Models\Company::all();
        $types = ['flight', 'car', 'hotel', 'concierge', 'general'];

        foreach ($companies as $company) {
            foreach ($types as $type) {
                \App\Models\TravelPolicy::create([
                    'company_id' => $company->id,
                    'policy_type' => $type,
                    'name' => ucfirst($type) . ' Standard Policy - ' . $company->name,
                    'description' => 'Standard ' . $type . ' travel policy for ' . $company->name . '. This policy defines the booking rules and limits.',
                    'is_active' => true,
                ]);
            }
        }
    }
}
