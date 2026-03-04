<?php

namespace App\Livewire\Passengerdetail;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;

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
    // ── Summary line items ────────────────────────────────────────────────────
    public array $summaryItems = [];

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        $this->selectedFlight = session('selected_flight');
        $this->searchParams = session('search_params');

        // If no flight selected, redirect back to search
        if (!$this->selectedFlight) {
            $this->redirect(route('flights.search'), navigate: true);
            return;
        }

        // Initialize from session summary
        $this->summaryItems = session('booking_summary', []);

        $counts = $this->searchParams['passengers'] ?? ['adults' => 1, 'children' => 0, 'infants' => 0];

        // Initialize passengers array
        $this->passengers = [];

        for ($i = 0; $i < ($counts['adults'] ?? 0); $i++) {
            $this->passengers[] = array_merge($this->emptyPassenger(), ['type' => 'ADULT']);
        }
        for ($i = 0; $i < ($counts['children'] ?? 0); $i++) {
            $this->passengers[] = array_merge($this->emptyPassenger(), ['type' => 'CHILD']);
        }
        for ($i = 0; $i < ($counts['infants'] ?? 0); $i++) {
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

    public function continue(): void
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

        // Next step is Payment/Confirm
        // For now, we will just dump the booking request, as Payment isn't built yet
        // $this->redirect(route('payment'), navigate: true);
    }

    public function back(): void
    {
        $this->redirect(route('seating'), navigate: true);
    }

    public function render()
    {
        return view('livewire.passengerdetails.passenger-details')
            ->layout('layouts.flight', ['title' => 'Passenger Details – FlightBook']);
    }

}
