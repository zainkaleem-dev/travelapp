<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Grade;
use App\Models\TravelPolicy;
use Illuminate\Database\Seeder;

class TravelPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            // Define policy definitions for each tier
            $tiers = [
                'premium' => [
                    'flight' => [
                        'name' => 'Executive First & Business Class Policy',
                        'description' => 'Allows booking of First Class and Business Class flights globally with premium airlines and maximum baggage allowance.',
                    ],
                    'hotel' => [
                        'name' => 'Luxury 5-Star Hotel & Suites Policy',
                        'description' => 'Allows luxury 5-star hotel bookings, executive suites, and higher nightly rate allowances.',
                    ],
                    'car' => [
                        'name' => 'Premium SUV & Executive Sedan Rental Policy',
                        'description' => 'Allows booking of luxury SUVs, premium sedans, and full-size vehicles with full insurance coverage.',
                    ],
                    'concierge' => [
                        'name' => 'VIP Fast-Track & Airport Lounge Access Policy',
                        'description' => 'Grants premium concierge benefits, airport meet-and-greets, VIP lounge access, and express check-in.',
                    ],
                    'general' => [
                        'name' => 'Platinum Level General Travel Allowance Policy',
                        'description' => 'High per-diem allowance, fully flexible travel booking windows, and high emergency budget allowances.',
                    ],
                ],
                'mid' => [
                    'flight' => [
                        'name' => 'Business Class / Premium Economy Flight Policy',
                        'description' => 'Allows Business Class bookings for long-haul flights (>6 hours) and Premium Economy for short-haul flights.',
                    ],
                    'hotel' => [
                        'name' => 'Standard Business 4-Star Hotel Policy',
                        'description' => 'Allows up to 4-star standard business hotels with moderate nightly budget limits.',
                    ],
                    'car' => [
                        'name' => 'Standard Mid-Size Sedan Rental Policy',
                        'description' => 'Allows booking of mid-size, standard sedans or compact SUVs for travel operations.',
                    ],
                    'concierge' => [
                        'name' => 'Standard Airport Concierge Support Policy',
                        'description' => 'Allows booking basic airport assistance, standard fast-track, and corporate parking benefits.',
                    ],
                    'general' => [
                        'name' => 'Gold Level General Travel Allowance Policy',
                        'description' => 'Standard corporate per-diem allowance and flexible bookings (up to 7 days advance purchase required).',
                    ],
                ],
                'budget' => [
                    'flight' => [
                        'name' => 'Economy Saver Flight Policy',
                        'description' => 'Strictly economy class bookings, utilizing budget/LCC airlines where possible, with minimum baggage allowance.',
                    ],
                    'hotel' => [
                        'name' => 'Budget 3-Star Business Hotel Policy',
                        'description' => 'Strictly standard business or budget 3-star hotels with strict nightly price caps.',
                    ],
                    'car' => [
                        'name' => 'Economy Compact Car Rental Policy',
                        'description' => 'Strictly compact or economy-class car rentals, standard base insurance only.',
                    ],
                    'concierge' => [
                        'name' => 'Standard Booking Support (No VIP Concierge)',
                        'description' => 'Basic online portal booking support. Concierge, VIP lounges, and fast-track are not authorized.',
                    ],
                    'general' => [
                        'name' => 'Silver Level General Travel Allowance Policy',
                        'description' => 'Basic corporate per-diem allowance, strictly non-flexible/non-refundable tickets with 14-day advance booking.',
                    ],
                ],
            ];

            // 1. Create the policies in database for this company
            $createdPolicies = [];
            foreach ($tiers as $tierKey => $policyTypes) {
                $createdPolicies[$tierKey] = [];
                foreach ($policyTypes as $type => $policyDetails) {
                    $policy = TravelPolicy::create([
                        'company_id' => $company->id,
                        'policy_type' => $type,
                        'name' => $policyDetails['name'] . ' - ' . $company->name,
                        'description' => $policyDetails['description'],
                        'is_active' => true,
                    ]);
                    $createdPolicies[$tierKey][$type] = $policy;
                }
            }

            // 2. Fetch all grades scoped to this company
            $grades = Grade::where('company_id', $company->id)->get();

            // 3. Assign policies to grades based on grade letter (A/B -> Premium, C/D -> Mid, E/others -> Budget)
            foreach ($grades as $grade) {
                $gradeName = $grade->name;
                $tier = 'budget'; // Default fallback

                if (str_starts_with($gradeName, 'Grade A') || str_starts_with($gradeName, 'Grade B')) {
                    $tier = 'premium';
                } elseif (str_starts_with($gradeName, 'Grade C') || str_starts_with($gradeName, 'Grade D')) {
                    $tier = 'mid';
                }

                // Attach all 5 policy types for this tier to the grade
                foreach (['flight', 'hotel', 'car', 'concierge', 'general'] as $type) {
                    if (isset($createdPolicies[$tier][$type])) {
                        $grade->travelPolicies()->attach($createdPolicies[$tier][$type]->id, [
                            'company_id' => $company->id
                        ]);
                    }
                }
            }
        }
    }
}
