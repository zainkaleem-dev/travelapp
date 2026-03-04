<?php

namespace App\Livewire\Additionalservices;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;

class AdditionalServices extends Component
{

    // ── Travel Insurance ─────────────────────────────────────────────────
    public string $insuranceOption = 'yes';   // 'yes' | 'no'
    public float $insurancePrice = 77.00;

    // ── Extra Baggage ────────────────────────────────────────────────────
    public bool $baggageEnabled = false;
    public int $baggageQty = 1;
    public float $baggagePrice = 59.00;   // price per unit

    // ── Flight Data ───────────────────────────────────────────────────────
    public array $selectedFlight = [];
    public array $searchParams = [];
    public array $availableFares = [];
    public int $passengerCount = 1;

    // ── Summary line items ────────────────────────────────────────────────
    public array $summaryItems = [];
    public float $totalBasePrice = 0.00;

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        // Load flight data
        $this->selectedFlight = session('selected_flight', []);
        $this->searchParams = session('search_params', []);
        $this->availableFares = session('selected_fare_tiers', []);

        // Require Flight session to exist
        if (empty($this->selectedFlight)) {
            $this->redirect(route('flights.search'), navigate: true);
            return;
        }

        // Calculate total passengers
        $counts = $this->searchParams['passengers'] ?? ['adults' => 1, 'children' => 0, 'infants' => 0];
        $this->passengerCount = ($counts['adults'] ?? 0) + ($counts['children'] ?? 0) + ($counts['infants'] ?? 0);
        if ($this->passengerCount < 1) {
            $this->passengerCount = 1;
        }

        // Load the base price (If we haven't visited PassengerDetails yet, we create the initial summary)
        if (!session()->has('booking_summary')) {
            $totalPrice = (float) ($this->selectedFlight['price'] ?? 0);
            $taxGuess = $totalPrice * 0.15; // Tax placeholder UI
            $basePrice = $totalPrice - $taxGuess;

            $this->summaryItems = [
                ['label' => 'Base Fare (x' . $this->passengerCount . ' Passengers)', 'removable' => false, 'amount' => round($basePrice, 2)],
                ['label' => 'Taxes and Fees', 'removable' => false, 'amount' => round($taxGuess, 2)],
            ];
            $this->totalBasePrice = $totalPrice;
        } else {
            $this->summaryItems = session('booking_summary', []);
            $this->totalBasePrice = session('booking_total', 0.00);
        }

        // Add the Baggage & Insurance placeholders to the summary if they don't exist yet
        $this->syncSummary();
    }

    private function syncSummary(): void
    {
        // We will append/update the Additional options onto the existing summary
        $baseItems = array_filter($this->summaryItems, function ($item) {
            return !in_array($item['label'], ['Travel Insurance', 'Extra Baggage']);
        });

        $this->summaryItems = $baseItems;

        if ($this->insuranceOption === 'yes') {
            $this->summaryItems[] = [
                'label' => 'Travel Insurance',
                'removable' => true,
                'amount' => $this->insurancePrice
            ];
        }

        if ($this->baggageEnabled && $this->baggageQty > 0) {
            $this->summaryItems[] = [
                'label' => 'Extra Baggage',
                'removable' => true,
                'amount' => ($this->baggagePrice * $this->baggageQty)
            ];
        }
    }

    // ── Computed ──────────────────────────────────────────────────────────────
    #[Computed]
    public function total(): float
    {
        return collect($this->summaryItems)->sum('amount');
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    public function setInsurance(string $value): void
    {
        $this->insuranceOption = $value;
        $this->syncSummary();
    }

    public function toggleBaggage(): void
    {
        $this->baggageEnabled = !$this->baggageEnabled;
        if (!$this->baggageEnabled) {
            $this->baggageQty = 1;
        }
        $this->syncSummary();
    }

    public function incrementBaggage(): void
    {
        $this->baggageQty++;
        $this->syncSummary();
    }

    public function decrementBaggage(): void
    {
        if ($this->baggageQty > 0) {
            $this->baggageQty--;
        }
        $this->syncSummary();
    }

    public function removeItem(int $index): void
    {
        if (isset($this->summaryItems[$index])) {
            $label = $this->summaryItems[$index]['label'];

            if ($label === 'Travel Insurance') {
                $this->insuranceOption = 'no';
            } elseif ($label === 'Extra Baggage') {
                $this->baggageEnabled = false;
            }

            $this->syncSummary();
        }
    }

    public function back(): void
    {
        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function continue(): void
    {
        // ── Store updated summary in session for the next step (Seating) ──
        session([
            'booking_summary' => $this->summaryItems,
            'booking_total' => $this->total()
        ]);

        $this->redirect(route('seating'), navigate: true);
    }

    public function render()
    {
        return view('livewire.additionalservices.additional-services')
            ->layout('layouts.flight', ['title' => 'Additional Services – FlightBook']);
    }

}
