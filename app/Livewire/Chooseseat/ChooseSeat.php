<?php

namespace App\Livewire\Chooseseat;

use App\Services\AmadeusService;
use Livewire\Component;

class ChooseSeat extends Component
{
    public array $selectedFlight = [];
    public array $seatMapData = [];
    public array $flightSegments = [];
    public int $currentSegmentIndex = 0;

    // ─── Seat configuration ──────────────────────────────────────────────────

    public ?string $selectedSeat = null;
    public float $selectedSeatPrice = 0.00;
    public string $selectedSeatCurrency = 'USD';

    public function mount(AmadeusService $amadeusService)
    {
        $this->selectedFlight = session('selected_flight', []);

        if (empty($this->selectedFlight)) {
            return $this->redirect(route('flights.search'), navigate: true);
        }

        try {
            // Use the raw offer from Amadeus to get the seat map
            $response = $amadeusService->getFlightSeatmap($this->selectedFlight['rawOffer']);
            $this->seatMapData = $response['data'] ?? [];

            if (!empty($this->seatMapData)) {
                // Initialize with the first map/segment
                $this->parseSeatMap(0);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ChooseSeat mount failed: ' . $e->getMessage());
        }
    }

    private function parseSeatMap(int $index)
    {
        $map = $this->seatMapData[$index] ?? null;
        if (!$map)
            return;

        // Current UI only shows one deck, we'll take the first one
        $deck = $map['decks'][0] ?? null;
        if (!$deck)
            return;

        // We could extract rows/cols here, but we'll do it in render for now
    }


    /** Toggle seat selection */
    public function selectSeat(string $seatId, float $price = 0.00): void
    {
        // Deselect if already selected
        if ($this->selectedSeat === $seatId) {
            $this->selectedSeat = null;
            $this->selectedSeatPrice = 0.00;
            return;
        }

        $this->selectedSeat = $seatId;
        $this->selectedSeatPrice = $price;
    }

    public function continue(): void
    {
        if ($this->selectedSeat) {
            session([
                'booking_seat' => $this->selectedSeat,
                'booking_seat_price' => $this->selectedSeatPrice,
                'booking_seat_currency' => $this->selectedSeatCurrency,
            ]);

            // Re-sync the summary
            $summary = session('booking_summary', []);
            // Find if seat already exists in summary and update or add
            $found = false;
            foreach ($summary as &$item) {
                if ($item['label'] === 'Seat Selection (' . $this->selectedSeat . ')') {
                    $item['amount'] = $this->selectedSeatPrice;
                    $found = true;
                    break;
                }
            }
            if (!$found && $this->selectedSeatPrice > 0) {
                $summary[] = [
                    'label' => 'Seat Selection (' . $this->selectedSeat . ')',
                    'removable' => true,
                    'amount' => $this->selectedSeatPrice
                ];
            }
            session(['booking_summary' => $summary]);
            session(['booking_total' => collect($summary)->sum('amount')]);
        }

        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function back(): void
    {
        $this->redirect(route('additional.services'), navigate: true);
    }

    public function render()
    {
        $rows = [];

        if (!empty($this->seatMapData)) {
            $map = $this->seatMapData[$this->currentSegmentIndex] ?? null;
            $deck = $map['decks'][0] ?? null;

            if ($deck) {
                foreach ($deck['seatingPlan'] as $planRow) {
                    $rowNumber = $planRow['rowNumber'];
                    $seats = [];
                    $isExtraLegroom = false;

                    foreach ($planRow['rowElements'] as $element) {
                        if (isset($element['seat'])) {
                            $seat = $element['seat'];
                            $seatId = $rowNumber . $seat['number'];
                            $occupied = ($seat['occupancyStatus'] ?? '') === 'OCCUPIED';

                            // Determine type/price
                            $price = 0;
                            if (isset($map['prices'])) {
                                // Find price for this seat reference
                                foreach ($map['prices'] as $p) {
                                    if (in_array($seatId, $p['seatId'] ?? [])) {
                                        $price = $p['base']['amount'] ?? 0;
                                        break;
                                    }
                                }
                            }

                            // Amenities check for extra legroom
                            $characteristics = $seat['characteristicsCodes'] ?? [];
                            $isExtra = in_array('XL', $characteristics) || in_array('LE', $characteristics);
                            if ($isExtra)
                                $isExtraLegroom = true;

                            $state = 'available';
                            if ($seatId === $this->selectedSeat)
                                $state = 'selected';
                            elseif ($occupied)
                                $state = 'occupied';
                            elseif ($isExtra)
                                $state = 'extra';

                            $seats[$seat['number']] = [
                                'id' => $seatId,
                                'state' => $state,
                                'price' => $price,
                                'isExtra' => $isExtra
                            ];
                        }
                    }

                    if (!empty($seats)) {
                        $rows[] = [
                            'number' => $rowNumber,
                            'isExtra' => $isExtraLegroom,
                            'seats' => $seats,
                        ];
                    }
                }
            }
        }

        return view('livewire.chooseseat.choose-seat', [
            'rows' => $rows,
        ])->layout('layouts.flight', ['title' => 'Choose Seat – FlightBook']);
    }
}