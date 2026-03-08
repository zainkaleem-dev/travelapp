<?php

namespace App\Livewire\Chooseseat;

use App\Services\AmadeusService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class ChooseSeat extends Component
{
    public array $selectedFlight = [];
    public array $seatMapData = [];
    public array $flightInfo = [];
    public array $columnHeaders = [];
    public int $passengerCount = 1;

    public int $currentPassengerIndex = 0;
    public int $currentSegmentIndex = 0;
    public array $passengerSeats = [];

    public ?string $selectedSeat = null;
    public float $selectedSeatPrice = 0.00;

    public string $currencyCode = 'USD';

    public array $rows = [];
    public array $leftCols = [];
    public array $rightCols = [];

    public function mount()
    {
        $this->selectedFlight = session('selected_flight');
        if (!$this->selectedFlight) {
            return redirect()->route('flights.list');
        }

        $searchParams = session('flight_search_params');
        if ($searchParams) {
            $this->passengerCount = ($searchParams['adultCount'] ?? 0) + ($searchParams['childCount'] ?? 0) + ($searchParams['infantCount'] ?? 0);
            $this->currencyCode = $searchParams['currency'] ?? 'USD';
            $this->currencyCode = $searchParams['currency'] ?? 'USD';
        } else {
            $this->passengerCount = 1;
        }
        if ($this->passengerCount < 1)
            $this->passengerCount = 1;

        // Pre-initialize passenger seats
        for ($i = 0; $i < $this->passengerCount; $i++) {
            $this->passengerSeats[$i] = ['id' => '', 'price' => 0];
        }

        $this->prepareFlightInfo();
        $this->loadSeatMap();
    }

    protected function prepareFlightInfo()
    {
        $offer = $this->selectedFlight;
        $itineraries = $offer['itineraries'] ?? [];

        foreach ($itineraries as $index => $itin) {
            // Using already mapped itinerary data from FlightList
            $this->flightInfo[] = [
                'type' => ($index === 0) ? 'Departure' : 'Return',
                'airlineCode' => $itin['airlineCode'] ?? '',
                'flightNumber' => $itin['flightNumber'] ?? '',
                'departureTime' => $itin['dep'] ?? '',
                'arrivalTime' => $itin['arr'] ?? '',
                'origin' => $itin['depAirport'] ?? '',
                'destination' => $itin['arrAirport'] ?? '',
                'duration' => $itin['duration'] ?? '',
                'stops' => $itin['stops'] ?? 'Direct',
            ];
        }
    }

    public function loadSeatMap()
    {
        $amadeus = app(AmadeusService::class);
        try {
            $offer = $this->selectedFlight['rawOffer'] ?? $this->selectedFlight;
            $response = $amadeus->getFlightSeatmap($offer);
            if (empty($response['data'])) {
                \Illuminate\Support\Facades\Log::warning('SeatMap API returned EMPTY data for flight ID: ' . ($offer['id'] ?? 'unknown'));
            }

            if (isset($response['data']) && !empty($response['data'])) {
                $this->seatMapData = $response['data'][0];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Seatmap load error: ' . $e->getMessage());
        }

        $this->parseSeatMap();
    }


    protected function parseSeatMap()
    {
        if (empty($this->seatMapData))
            return;
        $decks = $this->seatMapData['decks'] ?? [];
        $deck = $decks[0] ?? [];
        $seatingPlan = $deck['seatingPlan'] ?? [];

        $allLetters = [];
        $rows = [];

        foreach ($seatingPlan as $planRow) {
            $rowNum = $planRow['rowNumber'] ?? null;
            if (!$rowNum)
                continue;

            $rowSeats = [];
            $isExtra = false;

            $elements = $planRow['elements'] ?? [];
            foreach ($elements as $element) {
                if (($element['type'] ?? '') !== 'seat')
                    continue;

                $seat = $element['seat']['number'] ?? '';
                $letter = preg_replace('/[0-9]/', '', $seat);
                if ($letter)
                    $allLetters[] = $letter;

                $travelerPricing = $element['seat']['travelerPricing'][0] ?? [];
                $status = $travelerPricing['seatAvailabilityStatus'] ?? 'AVAILABLE';
                $price = (float) ($travelerPricing['price']['base']['amount'] ?? 0.00);

                $characteristics = $element['seat']['characteristicsCodes'] ?? [];
                $state = ($status === 'OCCUPIED') ? 'occupied' : 'available';
                if ($state === 'available' && in_array('XL', $characteristics)) {
                    $state = 'extra';
                    $isExtra = true;
                }

                $rowSeats[$letter] = [
                    'id' => $seat,
                    'price' => $price,
                    'state' => $state
                ];
            }

            if (!empty($rowSeats)) {
                $rows[] = [
                    'number' => $rowNum,
                    'isExtra' => $isExtra,
                    'seats' => $rowSeats
                ];
            }
        }

        $uniqueLetters = array_unique($allLetters);
        sort($uniqueLetters);

        $mid = ceil(count($uniqueLetters) / 2);
        $this->leftCols = array_slice($uniqueLetters, 0, $mid);
        $this->rightCols = array_slice($uniqueLetters, $mid);
        $this->rows = $rows;
    }

    public function selectPassenger($index)
    {
        $this->currentPassengerIndex = $index;
        $this->refreshSeatMapStates();
    }

    public function selectSeat($seatId, $price)
    {
        $currentSeat = $this->passengerSeats[$this->currentPassengerIndex]['id'] ?? '';

        if ($currentSeat === $seatId) {
            // Unselect
            $this->passengerSeats[$this->currentPassengerIndex] = ['id' => '', 'price' => 0];
        } else {
            // Check if occupied by another passenger in this session
            foreach ($this->passengerSeats as $idx => $s) {
                if ($idx !== $this->currentPassengerIndex && $s['id'] === $seatId) {
                    return; // Already taken by Px
                }
            }
            $this->passengerSeats[$this->currentPassengerIndex] = ['id' => $seatId, 'price' => (float) $price];
        }

        $this->refreshSeatMapStates();
    }

    protected function refreshSeatMapStates()
    {
        // Re-parse or just update the states in $this->rows for reactivity
        foreach ($this->rows as &$row) {
            foreach ($row['seats'] as $letter => &$seat) {
                // Reset to base state based on original data (simplified here)
                // In a perfect impl, we'd store original states.
                if ($seat['state'] === 'selected') {
                    $seat['state'] = 'available'; // Default back
                }

                // Apply selected state
                foreach ($this->passengerSeats as $s) {
                    if ($s['id'] === $seat['id']) {
                        $seat['state'] = 'selected';
                    }
                }
            }
        }
    }

    public function back()
    {
        $this->redirect(route('additional.services'), navigate: true);
    }

    public function continue()
    {
        $summary = session('booking_summary', []);

        // Remove old seat items
        $summary = array_filter($summary, fn($item) => !str_contains($item['label'], 'Seat Selection'));

        foreach ($this->passengerSeats as $idx => $seat) {
            if (!empty($seat['id'])) {
                $summary[] = [
                    'label' => "Seat Selection P" . ($idx + 1) . " (" . $seat['id'] . ")",
                    'amount' => $seat['price'],
                    'removable' => true
                ];
            }
        }

        session(['booking_summary' => array_values($summary)]);
        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function render()
    {
        return view('livewire.chooseseat.choose-seat');
    }
}