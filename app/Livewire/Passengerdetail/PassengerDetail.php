<?php

namespace App\Livewire\Passengerdetail;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use App\Helpers\CountryHelper;

#[Layout('layouts.flight')]
class PassengerDetail extends Component
{

    // ── Contact ──────────────────────────────────────────────────────────────
    public string $contactEmail = '';

    public string $phoneCode = '+90';

    public string $phoneNumber = '';

    public array $passengers = [];

    public function rules(): array
    {
        $validCodes = implode(',', CountryHelper::getDialCodes());
        $validNationalities = implode(',', CountryHelper::getCountryNames());
        $departDate = $this->searchParams['departDate'] ?? now();

        return [
            'contactEmail' => 'required|email',
            'phoneCode' => 'required|string|in:' . $validCodes,
            'phoneNumber' => 'required|string|min:7|max:20',
            'passengers' => 'required|array|min:1',
            'passengers.*.first_name' => 'required|string|min:2',
            'passengers.*.last_name' => 'required|string|min:2',
            'passengers.*.dob' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) use ($departDate) {
                    // Extract index
                    if (!preg_match('/passengers\.(\d+)\.dob/', $attribute, $matches)) return;
                    $index = $matches[1];
                    $type = $this->passengers[$index]['type'] ?? 'ADULT';
                    
                    $birthDate = \Carbon\Carbon::parse($value);
                    $travelDate = \Carbon\Carbon::parse($departDate);
                    $age = $birthDate->diffInYears($travelDate);

                    if ($type === 'ADULT' && $age < 12) {
                        $fail("Adults must be 12 years or older on the date of travel.");
                    } elseif ($type === 'CHILD' && ($age < 2 || $age >= 12)) {
                        $fail("Children must be between 2 and 11 years old on the date of travel.");
                    } elseif ($type === 'HELD_INFANT' && $age >= 2) {
                        $fail("Infants must be under 2 years old on the date of travel.");
                    }
                }
            ],
            'passengers.*.nationality' => 'required|string|in:' . $validNationalities,
            'passengers.*.gender' => 'required|string|in:Male,Female',
            'passengers.*.passport' => 'required|string|min:5',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'contactEmail' => 'contact email',
            'phoneCode' => 'country code',
            'phoneNumber' => 'phone number',
            'passengers.*.first_name' => 'first name',
            'passengers.*.last_name' => 'last name',
            'passengers.*.dob' => 'date of birth',
            'passengers.*.nationality' => 'nationality',
            'passengers.*.gender' => 'gender',
            'passengers.*.passport' => 'passport number (or ID)',
        ];
    }

    public function messages(): array
    {
        return [
            'contactEmail.required' => 'Contact email is required.',
            'contactEmail.email' => 'Please enter a valid email address.',

            'phoneCode.required' => 'Country code is required.',
            'phoneCode.in' => 'Please select a valid country code.',

            'phoneNumber.required' => 'Phone number is required.',
            'phoneNumber.min' => 'Phone number must be at least :min digits.',
            'phoneNumber.max' => 'Phone number must be at most :max characters.',

            'passengers.required' => 'At least one passenger is required.',
            'passengers.array' => 'Passenger details must be a valid list.',
            'passengers.min' => 'At least one passenger is required.',

            'passengers.*.first_name.required' => 'First name is required.',
            'passengers.*.first_name.min' => 'First name must be at least :min characters.',

            'passengers.*.last_name.required' => 'Last name is required.',
            'passengers.*.last_name.min' => 'Last name must be at least :min characters.',

            'passengers.*.dob.required' => 'Date of birth is required.',
            'passengers.*.dob.date' => 'Please enter a valid date of birth.',
            'passengers.*.dob.before' => 'Date of birth must be in the past.',

            'passengers.*.nationality.required' => 'Nationality is required.',
            'passengers.*.nationality.in' => 'Please select a valid nationality.',

            'passengers.*.gender.required' => 'Gender is required.',
            'passengers.*.gender.in' => 'Please select a valid gender.',

            'passengers.*.passport.required' => 'Passport number (or ID) is required.',
            'passengers.*.passport.min' => 'Passport number (or ID) must be at least :min characters.',
        ];
    }

    public array $selectedFlight = [];
    public array $searchParams = [];
    public string $currencyCode = 'USD';
    // ── Summary line items ────────────────────────────────────────────────────
    public array $summaryItems = [];

    // ── Accordion State ───────────────────────────────────────────────────────
    public int $activePassengerIndex = 0;

    public function setActivePassenger(int $index): void
    {
        $this->activePassengerIndex = $index;
    }

    public function nextPassenger(): void
    {
        if ($this->activePassengerIndex < count($this->passengers) - 1) {
            $this->activePassengerIndex++;
        }
    }

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount()
    {
        $this->selectedFlight = session('selected_flight');
        $this->searchParams = session('flight_search_params', []);

        // Initial currency code
        $this->currencyCode = $this->searchParams['currency'] ?? ($this->selectedFlight['currency'] ?? 'USD');

        // If no flight selected, redirect back to search
        if (!$this->selectedFlight) {
            return redirect()->route('flights.search');
        }

        // Initialize from session summary
        $this->summaryItems = session('booking_summary', []);

        $adults = $this->searchParams['adultCount'] ?? 1;
        $children = $this->searchParams['childCount'] ?? 0;
        $infants = $this->searchParams['infantCount'] ?? 0;

        // Initialize passengers array
        $this->passengers = [];

        for ($i = 0; $i < $adults; $i++) {
            $this->passengers[] = array_merge($this->emptyPassenger(), ['type' => 'ADULT']);
        }
        for ($i = 0; $i < $children; $i++) {
            $this->passengers[] = array_merge($this->emptyPassenger(), ['type' => 'CHILD']);
        }
        for ($i = 0; $i < $infants; $i++) {
            $this->passengers[] = array_merge($this->emptyPassenger(), ['type' => 'HELD_INFANT']);
        }

    }

    private function emptyPassenger(): array
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'dob' => '',
            'nationality' => '',
            'gender' => '',
            'passport' => '',
        ];
    }

    // ── Computed ──────────────────────────────────────────────────────────────
    #[Computed]
    public function countries(): array
    {
        return CountryHelper::getAllCountries();
    }

    #[Computed]
    public function total(): float
    {
        return collect($this->summaryItems)->sum('amount');
    }

    #[Computed]
    public function completedPassengers(): array
    {
        $completed = [];
        foreach ($this->passengers as $idx => $p) {
            $isFilled = !empty($p['first_name']) && 
                        !empty($p['last_name']) && 
                        !empty($p['dob']) && 
                        !empty($p['nationality']) && 
                        !empty($p['passport']);
            
            // Simple age check for the checkmark
            $ageValid = true;
            if (!empty($p['dob'])) {
                try {
                    $birthDate = \Carbon\Carbon::parse($p['dob']);
                    $travelDate = \Carbon\Carbon::parse($this->searchParams['departDate'] ?? now());
                    $age = $birthDate->diffInYears($travelDate);
                    $type = $p['type'] ?? 'ADULT';

                    if ($type === 'ADULT' && $age < 12) $ageValid = false;
                    elseif ($type === 'CHILD' && ($age < 2 || $age >= 12)) $ageValid = false;
                    elseif ($type === 'HELD_INFANT' && $age >= 2) $ageValid = false;
                } catch (\Exception $e) {
                    $ageValid = false;
                }
            }

            $completed[$idx] = $isFilled && $ageValid;
        }
        return $completed;
    }


    // ── Actions ───────────────────────────────────────────────────────────────
    public function removeItem(int $index): void
    {
        if (isset($this->summaryItems[$index]) && $this->summaryItems[$index]['removable']) {
            array_splice($this->summaryItems, $index, 1);
        }
    }

    public function continue(\App\Services\AmadeusService $amadeusService)
    {
        $this->validate();

        // ── Store passenger data in session for final Amadeus Booking ──
        session([
            'booking_contact' => [
                'email' => $this->contactEmail,
                'phoneCode' => $this->phoneCode,
                'phoneNumber' => $this->phoneNumber,
            ],
            'booking_passengers' => $this->passengers,
            'booking_summary' => $this->summaryItems,
            'booking_total' => $this->total()
        ]);

        try {
            // 1. Format frontend data to the strict API traveler array
            $amadeusTravelers = $amadeusService->formatPassengersForBooking(
                $this->passengers,
                session('booking_contact')
            );

            // 2. Fetch the originally selected offer (usually cached with Upsell changes)
            $rawOffer = $this->selectedFlight['rawOffer'] ?? null;
            if (!$rawOffer) {
                throw new \Exception("Original flight offer is missing from memory.");
            }

            // 3. Confirm pricing / inventory one last time before creating the order
            $pricingResponse = $amadeusService->priceFlightOffer([$rawOffer]);
            if (!isset($pricingResponse['data']['flightOffers'][0])) {
                throw new \Exception("Pricing confirmation failed. Seats may no longer be available.");
            }

            // We use the freshly validated price offer from Amadeus to ensure successful booking
            $validatedOffer = $pricingResponse['data']['flightOffers'][0];

            // 4. Submit Order
            $orderPayload = [
                'data' => [
                    'type' => 'flight-orders',
                    'flightOffers' => [$validatedOffer],
                    'travelers' => $amadeusTravelers
                ]
            ];

            $orderResult = $amadeusService->bookFlight($orderPayload);

            if (isset($orderResult['data']['id'])) {
                session([
                    'amadeus_booking_id' => $orderResult['data']['id'],
                    'amadeus_booking_reference' => $orderResult['data']['associatedRecords'][0]['reference'] ?? 'PENDING'
                ]);

                // Order perfectly created! Proceed to confirmation view.
                session()->flash('success', 'Flight successfully booked!');
                return redirect()->route('flight.confirmation');
            } else {
                $errorMsg = $orderResult['errors'][0]['detail'] ?? 'Amadeus rejected the booking order parameters.';
                session()->flash('error', $errorMsg);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Amadeus Booking Failed: ' . $e->getMessage());
            session()->flash('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    public function back()
    {
        return redirect()->route('seating');
    }

    public function render()
    {
        return view('livewire.passengerdetails.passenger-details');
    }

}
