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

    // ── Summary line items ────────────────────────────────────────────────────
    public array $summaryItems = [
        ['label' => 'Luggage Only Flight', 'removable' => false, 'amount' => 179.00],
        ['label' => 'Return Flight All', 'removable' => false, 'amount' => 1037.00],
        ['label' => 'Price Baggage 13kg', 'removable' => true, 'amount' => 17.00],
        ['label' => 'Travel Insurance', 'removable' => true, 'amount' => 17.00],
        ['label' => 'Seat Taxes', 'removable' => true, 'amount' => 17.00],
    ];

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount(int $passengerCount = 2): void
    {
        for ($i = 0; $i < $passengerCount; $i++) {
            $this->passengers[] = $this->emptyPassenger();
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
        return range(date('Y') - 18, 1930);
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

        // Redirect to next step (Additional Services)
        $this->redirect(route('additional-services'));
    }

    public function back(): void
    {
        $this->redirect(route('select-flight'));
    }

    public function render()
    {
        return view('livewire.passengerdetails.passenger-details')
            ->layout('layouts.flight', ['title' => 'Passenger Details – FlightBook']);
    }

}
