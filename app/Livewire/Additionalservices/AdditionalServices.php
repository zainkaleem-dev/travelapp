<?php

namespace App\Livewire\Additionalservices;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.flight')]
class AdditionalServices extends Component
{


    // ── Dynamic Services ──────────────────────────────────────────────────
    public array $includedBaggage = [];
    public array $availableAncillaries = [];
    public array $selectedAncillaries = [];

    // ── Flight Data ───────────────────────────────────────────────────────
    public array $selectedFlight = [];
    public array $searchParams = [];
    public array $availableFares = [];
    public int $passengerCount = 1;
    public string $selectedFareName = '';

    // ── Summary line items ────────────────────────────────────────────────
    public array $summaryItems = [];
    public float $totalBasePrice = 0.00;
    public string $currencyCode = 'USD';

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        // Load flight data
        $this->selectedFlight = session('selected_flight', []);
        $this->searchParams = session('flight_search_params', []);
        $this->availableFares = session('selected_fare_tiers', []);

        Log::info('ADDITIONAL_SERVICES_LOADED_FARES', [
            'count' => count($this->availableFares),
            'cabins' => array_keys($this->availableFares)
        ]);

        Log::info('ADDITIONAL_SERVICES_MOUNT_DATA', [
            'search_params' => $this->searchParams,
            'fares' => $this->availableFares
        ]);

        Log::info('RAW_AMADEUS_OFFER_INSPECTION', [
            'raw_offer' => $this->selectedFlight['rawOffer'] ?? null,
            'fares' => $this->availableFares
        ]);

        // Require Flight session to exist
        if (empty($this->selectedFlight)) {
            $this->redirect(route('flights.search'));
            return;
        }

        // Calculate total passengers
        $this->passengerCount =
            ($this->searchParams['adultCount'] ?? 1) +
            ($this->searchParams['childCount'] ?? 0) +
            ($this->searchParams['infantCount'] ?? 0);

        if ($this->passengerCount < 1) {
            $this->passengerCount = 1;
        }

        // Currency
        $this->currencyCode = $this->searchParams['currency'] ?? ($this->selectedFlight['currency'] ?? 'USD');

        // Initial summary load
        if (session()->has('booking_summary')) {
            $sessItems = session('booking_summary', []);
            $newItems = [];
            foreach ($sessItems as $item) {
                if (str_starts_with($item['label'], 'Base Fare')) {
                    // Convert old legacy session Base Fare to Flight Total
                    $item['label'] = 'Flight Total (x' . $this->passengerCount . ' Passengers)';
                    $item['amount'] = (float) ($this->selectedFlight['price'] ?? 0);
                    $newItems[] = $item;
                } elseif ($item['label'] !== 'Taxes and Fees') {
                    $newItems[] = $item;
                }
            }
            $this->summaryItems = $newItems;
            $this->totalBasePrice = (float) session('booking_total', 0.00);
        } else {
            $totalPrice = (float) ($this->selectedFlight['price'] ?? 0);

            $this->summaryItems = [
                ['label' => 'Flight Total (x' . $this->passengerCount . ' Passengers)', 'removable' => false, 'amount' => round($totalPrice, 2)],
            ];
            $this->totalBasePrice = $totalPrice;
        }

        // Extract Dynamic Data
        $this->extractDynamicData();

        // Sync summary items
        $this->syncSummary();

        // Default Fare Selection
        if (!empty($this->availableFares)) {
            $firstCabin = array_key_first($this->availableFares);
            if (!empty($this->availableFares[$firstCabin])) {
                $this->selectedFareName = $this->availableFares[$firstCabin][0]['name'] ?? '';
            }
        }
    }


    private function extractDynamicData(): void
    {
        $rawOffer = $this->selectedFlight['rawOffer'] ?? [];
        if (empty($rawOffer))
            return;

        // 1. Extract Included Baggage — aggregate across all segments into one entry
        $travelerPricings = $rawOffer['travelerPricings'] ?? [];
        if (!empty($travelerPricings)) {
            $fareDetails = $travelerPricings[0]['fareDetailsBySegment'] ?? [];
            $totalQty = 0;
            $maxWeight = null;
            $weightUnit = null;

            foreach ($fareDetails as $detail) {
                $baggage = $detail['includedCheckedBags'] ?? [];
                if (!empty($baggage)) {
                    $totalQty += (int) ($baggage['quantity'] ?? 0);
                    $w = $baggage['weight'] ?? null;
                    if ($w !== null && ($maxWeight === null || $w > $maxWeight)) {
                        $maxWeight = $w;
                        $weightUnit = $baggage['weightUnit'] ?? 'KG';
                    }
                }

                // 2. Extract Amenities
                $amenities = $detail['amenities'] ?? [];
                foreach ($amenities as $amenity) {
                    $this->availableAncillaries[] = [
                        'code' => $amenity['code'] ?? 'UNK',
                        'description' => $amenity['description'] ?? 'Unnamed Service',
                        'isChargeable' => $amenity['isChargeable'] ?? false,
                        'amenityType' => $amenity['amenityType'] ?? 'N/A',
                    ];
                }
            }

            if ($totalQty > 0 || $maxWeight !== null) {
                $this->includedBaggage[] = [
                    'quantity' => $totalQty > 0 ? $totalQty : null,
                    'weight' => $maxWeight,
                    'weightUnit' => $weightUnit,
                ];
            }
        }

        // De-duplicate ancillaries
        $uniqueAncillaries = [];
        foreach ($this->availableAncillaries as $anc) {
            $uniqueAncillaries[$anc['code']] = $anc;
        }
        $this->availableAncillaries = array_values($uniqueAncillaries);
    }

    private function syncSummary(): void
    {
        // 1. Remove optional items that we manage dynamically
        $this->summaryItems = array_filter($this->summaryItems, function ($item) {
            return strpos($item['label'], 'Ancillary:') === false;
        });

        // 2. Add Selected Ancillaries (Lounges, etc.)
        // Chargeable ancillaries (price = null) are listed as $0 with a label noting
        // the final price is confirmed at checkout. Free ones show $0.
        foreach ($this->selectedAncillaries as $code => $anc) {
            $label = ucwords(strtolower($anc['description']));
            $isChargeable = $anc['isChargeable'] ?? false;
            $this->summaryItems[] = [
                'label' => 'Ancillary: ' . $label . ($isChargeable ? ' (price at checkout)' : ''),
                'removable' => true,
                'amount' => 0.00, // Chargeable ancillaries priced at checkout via Amadeus
                'code' => $code
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
    public function selectFare(string $cabinCode, int $index): void
    {
        $fare = $this->availableFares[$cabinCode][$index] ?? null;

        if (!$fare) {
            return;
        }

        $this->selectedFareName = $fare['name'];
        $newPrice = (float) $fare['price'];

        $this->selectedFlight['price'] = $newPrice;
        $this->selectedFlight['rawOffer'] = $fare['raw'] ?? $this->selectedFlight['rawOffer'];

        // Update the base fare in summary and remove any lingering taxes line
        foreach ($this->summaryItems as $key => &$item) {
            if (str_starts_with($item['label'], 'Flight Total') || str_starts_with($item['label'], 'Base Fare')) {
                $item['label'] = 'Flight Total (x' . $this->passengerCount . ' Passengers)';
                $item['amount'] = round($newPrice, 2);
            } elseif ($item['label'] === 'Taxes and Fees') {
                unset($this->summaryItems[$key]);
            }
        }
        $this->summaryItems = array_values($this->summaryItems); // Re-index after unset

        $this->totalBasePrice = $newPrice;

        $this->syncSummary();

        // Update session immediately
        session([
            'selected_flight' => $this->selectedFlight,
            'booking_summary' => $this->summaryItems,
            'booking_total' => $this->total()
        ]);

        session()->flash('success', "Selected {$this->selectedFareName} fare successfully. Price updated.");
    }

    public function toggleAncillary(string $code): void
    {
        if (isset($this->selectedAncillaries[$code])) {
            unset($this->selectedAncillaries[$code]);
        } else {
            foreach ($this->availableAncillaries as $anc) {
                if ($anc['code'] === $code) {
                    // Only assign a price for free amenities; chargeable ones
                    // require a separate pricing call (price not in the base offer).
                    $price = $anc['isChargeable'] ? null : 0.00;
                    $this->selectedAncillaries[$code] = array_merge($anc, ['price' => $price]);
                    break;
                }
            }
        }
        $this->syncSummary();
    }

    public function removeItem(int $index): void
    {
        if (isset($this->summaryItems[$index])) {
            $item = $this->summaryItems[$index];
            $label = $item['label'];

            if (strpos($label, 'Ancillary:') === 0) {
                $code = $item['code'] ?? null;
                if ($code)
                    unset($this->selectedAncillaries[$code]);
            }

            $this->syncSummary();
        }
    }

    public function back(): void
    {
        $this->redirect(route('flights.list'), navigate: true);
    }

    public function goToSeating(): void
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
        return view('livewire.additionalservices.additional-services');
    }

}
