<?php

namespace App\Livewire\Passengerdetail;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class PassengerDetail extends Component
{

    // ── Contact ──────────────────────────────────────────────────────────────
    #[Rule('required|email')]
    public string $contactEmail = '';

    #[Rule('required|string|in:+1,+44,+49,+90')]
    public string $phoneCode = '+90';

    #[Rule('required|string|min:7|max:20')]
    public string $phoneNumber = '';

    // ── Passengers (array of passenger data) ─────────────────────────────────
    // Each item: ['first_name','last_name','dob_day','dob_month','dob_year','nationality','gender','passport']
    #[Rule([
        'passengers' => 'required|array|min:1',
        'passengers.*.first_name' => 'required|string|min:2',
        'passengers.*.last_name' => 'required|string|min:2',
        'passengers.*.dob_day' => 'required|integer|between:1,31',
        'passengers.*.dob_month' => 'required|string|not_in:Month',
        'passengers.*.dob_year' => 'required|integer|min:1930',
        'passengers.*.nationality' => 'required|string|not_in:Select',
        'passengers.*.gender' => 'required|string|in:Male,Female',
        'passengers.*.passport' => 'required|string|min:5',
    ])]
    public array $passengers = [];

    public array $selectedFlight = [];
    public array $searchParams = [];
    // ── Summary line items ────────────────────────────────────────────────────
    public array $summaryItems = [];

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount()
    {
        $this->selectedFlight = session('selected_flight');
        $this->searchParams = session('flight_search_params', []);

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
            'dob_day' => '',
            'dob_month' => '',
            'dob_year' => '',
            'nationality' => '',
            'gender' => '',
            'passport' => '',
        ];
    }

    // ── Computed ──────────────────────────────────────────────────────────────
    #[Computed]
    public function total(): float
    {
        return collect($this->summaryItems)->sum('amount');
    }

    #[Computed]
    public function days(): array
    {
        return range(1, 31);
    }

    #[Computed]
    public function months(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

    #[Computed]
    public function years(): array
    {
        // For adults, minimum 12 years old
        return range(date('Y'), 1930);
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
