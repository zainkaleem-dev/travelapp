<?php

namespace App\Livewire\Chooseseat;

use App\Services\AmadeusService;
use Livewire\Component;

class ChooseSeat extends Component
{
    public array $selectedFlight = [];
    public array $seatMapData = [];
    public array $flightInfo = [];   // dynamic route data for the blade header
    public array $columnHeaders = [];   // seat column letters from actual aircraft layout
    public int $passengerCount = 1;

    public int $currentPassengerIndex = 0;
    public int $currentSegmentIndex = 0;
    public array $passengerSeats = []; // [index => ['id' => '1A', 'price' => 20.00]]

    // ─── Seat configuration (Legacy, keeping for compatibility) ──────────────
    public ?string $selectedSeat = null;
    public float $selectedSeatPrice = 0.00;
    public string $selectedSeatCurrency = 'USD';

    // ─── Summary ─────────────────────────────────────────────────────────────
    public array $summaryItems = [];
    public float $totalBasePrice = 0.00;

    public function mount(AmadeusService $amadeusService)
    {
        $this->selectedFlight = session('selected_flight', []);

        if (empty($this->selectedFlight)) {
            return $this->redirect(route('flights.search'), navigate: true);
        }

        // ── Populate flight info for the blade ────────────────────────────
        $this->buildFlightInfo();

        // ── Passengers ───────────────────────────────────────────────────
        $counts = session('search_params.passengers', ['adults' => 1, 'children' => 0, 'infants' => 0]);
        $this->passengerCount = max(
            1,
            ($counts['adults'] ?? 0) + ($counts['children'] ?? 0) + ($counts['infants'] ?? 0)
        );

        // Load existing selections if any
        $this->passengerSeats = session('booking_passenger_seats', []);

        // Sync legacy props for the first passenger
        $this->selectedSeat = $this->passengerSeats[$this->currentPassengerIndex]['id'] ?? null;
        $this->selectedSeatPrice = $this->passengerSeats[$this->currentPassengerIndex]['price'] ?? 0.00;

        // ── Load summary from session ─────────────────────────────────────
        $this->summaryItems = session('booking_summary', []);
        $this->totalBasePrice = (float) session('booking_total', 0.00);

        // ── Fetch seat map from Amadeus ───────────────────────────────────
        try {
            $rawOffer = $this->selectedFlight['rawOffer'] ?? null;
            if ($rawOffer) {
                $response = $amadeusService->getFlightSeatmap($rawOffer);
                $this->seatMapData = $response['data'] ?? [];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ChooseSeat seatmap failed: ' . $e->getMessage());
            $this->seatMapData = [];
        }
    }

    public function selectPassenger(int $index): void
    {
        $this->currentPassengerIndex = $index;
        // Sync legacy props
        $this->selectedSeat = $this->passengerSeats[$index]['id'] ?? null;
        $this->selectedSeatPrice = $this->passengerSeats[$index]['price'] ?? 0.00;
    }

    // ── Build human-readable flight info from session ─────────────────────────
    private function buildFlightInfo(): void
    {
        $itineraries = $this->selectedFlight['itineraries'] ?? [];
        $flightDetails = [];

        foreach ($itineraries as $itin) {
            $segments = $itin['segments'] ?? [];
            if (empty($segments))
                continue;

            $first = $segments[0];
            $last = end($segments);

            $flightDetails[] = [
                'type' => count($flightDetails) === 0 ? 'Outbound' : 'Inbound',
                'airlineCode' => $itin['airlineCode'] ?? '—',
                'flightNumber' => ($itin['airlineCode'] ?? '') . ($itin['flightNumber'] ?? ''),
                'origin' => $itin['depAirport'] ?? '—',
                'originCity' => $itin['depCity'] ?? $itin['depAirport'] ?? '—',
                'destination' => $itin['arrAirport'] ?? '—',
                'destCity' => $itin['arrCity'] ?? $itin['arrAirport'] ?? '—',
                'departureTime' => $itin['dep'] ?? '—',
                'arrivalTime' => $itin['arr'] ?? '—',
                'duration' => $itin['duration'] ?? '—',
                'stops' => $itin['stops'] ?? 0,
            ];
        }

        // Final fallback if itineraries are empty
        if (empty($flightDetails)) {
            $flightDetails[] = [
                'type' => 'Flight',
                'airlineCode' => $this->selectedFlight['airlineCode'] ?? '—',
                'flightNumber' => $this->selectedFlight['flightNumber'] ?? '—',
                'origin' => $this->selectedFlight['origin'] ?? '—',
                'originCity' => $this->selectedFlight['origin'] ?? '—',
                'destination' => $this->selectedFlight['destination'] ?? '—',
                'destCity' => $this->selectedFlight['destination'] ?? '—',
                'departureTime' => $this->selectedFlight['departureTime'] ?? '—',
                'arrivalTime' => $this->selectedFlight['arrivalTime'] ?? '—',
                'duration' => $this->selectedFlight['duration'] ?? '—',
                'stops' => $this->selectedFlight['stops'] ?? 0,
            ];
        }

        $this->flightInfo = $flightDetails;
    }

    /** Toggle seat selection */
    public function selectSeat(string $seatId, float $price = 0.00): void
    {
        // Check if THIS passenger already has THIS seat
        if (isset($this->passengerSeats[$this->currentPassengerIndex]) && $this->passengerSeats[$this->currentPassengerIndex]['id'] === $seatId) {
            unset($this->passengerSeats[$this->currentPassengerIndex]);
            $this->selectedSeat = null;
            $this->selectedSeatPrice = 0.00;
            return;
        }

        // Check if ANY OTHER passenger has this seat
        foreach ($this->passengerSeats as $idx => $seat) {
            if ($idx !== $this->currentPassengerIndex && $seat['id'] === $seatId) {
                return; // Seat taken by another passenger in this booking
            }
        }

        $this->passengerSeats[$this->currentPassengerIndex] = [
            'id' => $seatId,
            'price' => (float) $price
        ];

        // Sync legacy props
        $this->selectedSeat = $seatId;
        $this->selectedSeatPrice = (float) $price;
    }

    public function continue(): void
    {
        $summary = session('booking_summary', []);

        // Remove all existing seat selection entries
        $summary = array_filter($summary, fn($item) => !str_starts_with($item['label'] ?? '', 'Seat Selection'));
        $summary = array_values($summary);

        // Add each selected seat to summary
        foreach ($this->passengerSeats as $idx => $seat) {
            if (isset($seat['price']) && $seat['price'] > 0) {
                $summary[] = [
                    'label' => 'Seat Selection P' . ($idx + 1) . ' (' . $seat['id'] . ')',
                    'removable' => true,
                    'amount' => (float) $seat['price'],
                ];
            }
        }

        session([
            'booking_passenger_seats' => $this->passengerSeats,
            'booking_summary' => $summary,
            'booking_total' => collect($summary)->sum('amount'),
        ]);

        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function back(): void
    {
        $this->redirect(route('additional.services'), navigate: true);
    }

    public function render()
    {
        $rows = [];
        $columnHeaders = [];

        if (!empty($this->seatMapData)) {
            $map = $this->seatMapData[$this->currentSegmentIndex ?? 0] ?? null;
            $deck = $map['decks'][0] ?? null;

            if ($deck && isset($deck['seatingPlan'])) {
                foreach ($deck['seatingPlan'] as $planRow) {
                    $rowNumber = $planRow['rowNumber'];
                    $seats = [];
                    $isExtraLegroom = false;

                    foreach ($planRow['rowElements'] as $element) {
                        if (!isset($element['seat'])) {
                            continue;
                        }

                        $seat = $element['seat'];
                        $seatId = $rowNumber . $seat['number'];
                        $col = $seat['number'];

                        // Track unique column letters
                        if (!in_array($col, $columnHeaders)) {
                            $columnHeaders[] = $col;
                        }

                        $occupied = ($seat['occupancyStatus'] ?? '') === 'OCCUPIED';
                        $characteristics = $seat['characteristicsCodes'] ?? [];
                        $isExtra = in_array('XL', $characteristics) || in_array('LE', $characteristics);

                        if ($isExtra) {
                            $isExtraLegroom = true;
                        }

                        // Price lookup
                        $price = 0;
                        if (isset($map['prices'])) {
                            foreach ($map['prices'] as $p) {
                                if (in_array($seatId, $p['seatId'] ?? [])) {
                                    $price = (float) ($p['base']['amount'] ?? 0);
                                    break;
                                }
                            }
                        }

                        $state = 'available';

                        // Check if THIS passenger has selected this seat
                        if ($seatId === ($this->passengerSeats[$this->currentPassengerIndex]['id'] ?? null)) {
                            $state = 'selected';
                        }
                        // Check if ANY ANOTHER passenger in this booking has selected this seat
                        else {
                            foreach ($this->passengerSeats as $idx => $s) {
                                if ($idx !== $this->currentPassengerIndex && ($s['id'] ?? null) === $seatId) {
                                    $state = 'occupied';
                                    break;
                                }
                            }
                        }

                        // If still available, check API occupancy status
                        if ($state === 'available') {
                            if ($occupied) {
                                $state = 'occupied';
                            } elseif ($isExtra) {
                                $state = 'extra';
                            }
                        }

                        $seats[$col] = [
                            'id' => $seatId,
                            'state' => $state,
                            'price' => $price,
                            'isExtra' => $isExtra,
                        ];
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

        // Sort column headers (A, B, C ... then D, E, F etc.)
        sort($columnHeaders);
        $this->columnHeaders = $columnHeaders;

        // Split column headers into left and right groups around the aisle
        $mid = (int) ceil(count($columnHeaders) / 2);
        $leftCols = array_slice($columnHeaders, 0, $mid);
        $rightCols = array_slice($columnHeaders, $mid);

        return view('livewire.chooseseat.choose-seat', [
            'rows' => $rows,
            'leftCols' => $leftCols,
            'rightCols' => $rightCols,
            'flightInfo' => $this->flightInfo,
        ])->layout('layouts.flight', ['title' => 'Choose Seat – FlightBook']);
    }
}