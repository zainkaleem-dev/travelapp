<?php

namespace App\Livewire\Chooseseat;

use App\Services\AmadeusService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class ChooseSeat extends Component
{
    public array $selectedFlight = [];
    public array $availableFares = [];
    public string $selectedFareName = '';
    public array $availableAncillaries = [];
    public array $selectedAncillaries = [];
    public array $seatMapData = [];
    public array $flightInfo = [];
    public array $columnHeaders = [];
    public int $passengerCount = 1;

    public int $currentPassengerIndex = 0;
    public int $currentSegmentIndex = 0;
    public array $passengerSeats = []; // [segmentIndex][passengerIndex] = ['id' => '', 'price' => 0]
    public array $allSeatMaps = []; // [segmentIndex] = seatMapData

    public ?string $selectedSeat = null;
    public float $selectedSeatPrice = 0.00;

    public string $currencyCode = 'USD';

    public array $rows = [];
    public array $leftCols = [];
    public array $rightCols = [];

    public array $searchParams = [];
    public array $summaryItems = [];
    public float $total = 0.0;

    public function mount()
    {
        $this->selectedFlight = session('selected_flight');
        if (!$this->selectedFlight) {
            return redirect()->route('flights.list');
        }

        $this->searchParams = session('flight_search_params', []);

        // Load Fares and Ancillaries from session (synced from FlightList/AdditionalServices)
        $this->availableFares = session('selected_fare_tiers', []);
        $this->selectedFareName = session('selected_fare_name', '');
        $this->availableAncillaries = session('available_ancillaries', []);
        $this->selectedAncillaries = session('selected_ancillaries', []);

        if ($this->searchParams) {
            $this->passengerCount = ($this->searchParams['adultCount'] ?? 0) + ($this->searchParams['childCount'] ?? 0) + ($this->searchParams['infantCount'] ?? 0);
            $this->currencyCode = $this->searchParams['currency'] ?? 'USD';
        } else {
            $this->passengerCount = 1;
        }

        if ($this->passengerCount < 1) {
            $this->passengerCount = 1;
        }

        // Initialize passenger seats for ALL segments
        $itineraries = $this->selectedFlight['rawOffer']['itineraries'] ?? [];
        $totalSegments = 0;
        foreach ($itineraries as $itin) {
            foreach ($itin['segments'] ?? [] as $seg) {
                for ($i = 0; $i < $this->passengerCount; $i++) {
                    $this->passengerSeats[$totalSegments][$i] = ['id' => '', 'price' => 0];
                }
                $totalSegments++;
            }
        }

        $this->summaryItems = session('booking_summary', []);
        $this->calculateTotal();

        $this->prepareFlightInfo();
        $this->loadSeatMap();
    }

    protected function calculateTotal()
    {
        $baseTotal = (float) session('booking_total', 0);
        $seatsTotal = 0;
        foreach ($this->passengerSeats as $segmentSeats) {
            $seatsTotal += collect($segmentSeats)->sum('price');
        }
        $this->total = $baseTotal + $seatsTotal;
    }

    protected function prepareFlightInfo()
    {
        $this->flightInfo = [];
        $itineraries = $this->selectedFlight['rawOffer']['itineraries'] ?? [];
        $segIdx = 0;

        foreach ($itineraries as $itinIdx => $itin) {
            foreach ($itin['segments'] ?? [] as $sIdx => $segment) {
                $this->flightInfo[] = [
                    'index' => $segIdx,
                    'segmentId' => $segment['id'] ?? null,
                    'type' => ($itinIdx === 0) ? 'Departure' : 'Return',
                    'airlineCode' => $segment['carrierCode'] ?? '',
                    'flightNumber' => ($segment['carrierCode'] ?? '') . ($segment['number'] ?? ''),
                    'departureTime' => \Carbon\Carbon::parse($segment['departure']['at'])->format('H:i'),
                    'arrivalTime' => \Carbon\Carbon::parse($segment['arrival']['at'])->format('H:i'),
                    'origin' => $segment['departure']['iataCode'] ?? '',
                    'destination' => $segment['arrival']['iataCode'] ?? '',
                ];
                $segIdx++;
            }
        }
    }

    public function selectFare($cabin, $index)
    {
        $fare = $this->availableFares[$cabin][$index] ?? null;
        if ($fare) {
            $this->selectedFareName = $fare['name'];
            $this->selectedFlight['price'] = $fare['price'];
            $this->selectedFlight['basePrice'] = $fare['basePrice'];

            // Update session
            session(['selected_fare_name' => $this->selectedFareName]);
            $selectedFlight = session('selected_flight');
            $selectedFlight['price'] = $fare['price'];
            $selectedFlight['basePrice'] = $fare['basePrice'];
            session(['selected_flight' => $selectedFlight]);

            $this->dispatch('fareUpdated');
        }
    }

    public function toggleAncillary($code)
    {
        if (isset($this->selectedAncillaries[$code])) {
            unset($this->selectedAncillaries[$code]);
        } else {
            $ancillary = collect($this->availableAncillaries)->firstWhere('code', $code);
            if ($ancillary) {
                $this->selectedAncillaries[$code] = $ancillary;
            }
        }
        session(['selected_ancillaries' => $this->selectedAncillaries]);
    }

    public function changeSegment($index)
    {
        $this->currentSegmentIndex = $index;
        $this->loadSeatMap();
    }

    public function loadSeatMap()
    {
        $currentSegment = $this->flightInfo[$this->currentSegmentIndex] ?? null;
        $segmentId = $currentSegment['segmentId'] ?? null;

        \Illuminate\Support\Facades\Log::info("Loading Seatmap for Segment Index: {$this->currentSegmentIndex}, ID: {$segmentId}");

        // Check cache first
        if ($segmentId && isset($this->allSeatMaps[$segmentId])) {
            $this->seatMapData = $this->allSeatMaps[$segmentId];
            $this->parseSeatMap();
            $this->refreshSeatMapStates();
            return;
        }

        $amadeus = app(AmadeusService::class);
        try {
            $offer = $this->selectedFlight['rawOffer'] ?? $this->selectedFlight;

            // Fetch all seatmaps for the offer
            $response = $amadeus->getFlightSeatmap($offer);

            $offerSegmentIds = [];
            foreach ($offer['itineraries'] ?? [] as $itin) {
                foreach ($itin['segments'] ?? [] as $s) {
                    $offerSegmentIds[] = $s['id'] ?? 'N/A';
                }
            }
            \Illuminate\Support\Facades\Log::info('Flight Offer Segment IDs:', ['ids' => $offerSegmentIds]);
            \Illuminate\Support\Facades\Log::info('Seatmap fetched.');

            if (isset($response['data']) && !empty($response['data'])) {
                // Store all seatmaps indexed by segmentId
                foreach ($response['data'] as $seatMap) {
                    $sid = $seatMap['segmentId'] ?? null;
                    if ($sid) {
                        $this->allSeatMaps[$sid] = $seatMap;
                    }
                }

                // Pick the current one based on segmentId
                if ($segmentId) {
                    $this->seatMapData = $this->allSeatMaps[$segmentId] ?? $response['data'][0] ?? [];
                } else {
                    $this->seatMapData = $response['data'][0] ?? [];
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Seatmap API returned empty data for offer', ['offer' => $offer]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Seatmap load error: ' . $e->getMessage());
        }

        $this->parseSeatMap();
        $this->refreshSeatMapStates();
    }


    protected function parseSeatMap()
    {
        if (empty($this->seatMapData))
            return;

        $decks = $this->seatMapData['decks'] ?? [];
        $deck = $decks[0] ?? [];
        $seats = $deck['seats'] ?? [];

        $allLetters = [];
        $rowsData = [];

        foreach ($seats as $seatElement) {
            $seatNumber = $seatElement['number'] ?? '';
            if (!$seatNumber)
                continue;

            preg_match('/^(\d+)([A-Z]+)$/', $seatNumber, $matches);
            if (count($matches) < 3)
                continue;

            $rowNum = $matches[1];
            $letter = $matches[2];

            if ($letter) {
                $allLetters[] = $letter;
            }

            $travelerPricing = $seatElement['travelerPricing'][0] ?? [];
            $status = $travelerPricing['seatAvailabilityStatus'] ?? 'AVAILABLE';

            $priceBase = $travelerPricing['price']['base'] ?? 0;
            $priceTotal = $travelerPricing['price']['total'] ?? 0;
            $price = (float) ($priceBase ?: $priceTotal);

            $characteristics = $seatElement['characteristicsCodes'] ?? [];
            $state = ($status === 'OCCUPIED' || $status === 'BLOCKED') ? 'occupied' : 'available';

            $isExtra = false;
            if ($state === 'available' && (in_array('XL', $characteristics) || in_array('1A_AQC_PREMIUM_SEAT', $characteristics))) {
                $state = 'extra';
                $isExtra = true;
            }

            if (!isset($rowsData[$rowNum])) {
                $rowsData[$rowNum] = [
                    'number' => $rowNum,
                    'isExtra' => false,
                    'seats' => []
                ];
            }

            if ($isExtra) {
                $rowsData[$rowNum]['isExtra'] = true;
            }

            $rowsData[$rowNum]['seats'][$letter] = [
                'id' => $seatNumber,
                'price' => $price,
                'state' => $state
            ];
        }

        ksort($rowsData, SORT_NUMERIC);
        $rows = array_values($rowsData);

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
        $currentSeat = $this->passengerSeats[$this->currentSegmentIndex][$this->currentPassengerIndex]['id'] ?? '';

        if ($currentSeat === $seatId) {
            // Unselect
            $this->passengerSeats[$this->currentSegmentIndex][$this->currentPassengerIndex] = ['id' => '', 'price' => 0];
        } else {
            // Check if occupied by another passenger in THIS specific segment
            foreach ($this->passengerSeats[$this->currentSegmentIndex] as $idx => $s) {
                if ($idx !== $this->currentPassengerIndex && $s['id'] === $seatId) {
                    return; // Already taken by Px in this segment
                }
            }
            $this->passengerSeats[$this->currentSegmentIndex][$this->currentPassengerIndex] = ['id' => $seatId, 'price' => (float) $price];
        }

        $this->calculateTotal();
        $this->refreshSeatMapStates();
    }

    public function removeItem($index)
    {
        if (isset($this->summaryItems[$index])) {
            $item = $this->summaryItems[$index];
            if ($item['removable'] ?? false) {
                // Handle Seat Selection removal specifically to sync with passengerSeats
                if (str_contains($item['label'], 'Seat Selection')) {
                    // Extract segment and passenger info from label if possible
                    // Format: "Seat Selection S1-P1 (12A)"
                    if (preg_match('/S(\d+)-P(\d+)/', $item['label'], $matches)) {
                        $sIdx = (int) $matches[1] - 1;
                        $pIdx = (int) $matches[2] - 1;
                        if (isset($this->passengerSeats[$sIdx][$pIdx])) {
                            $this->passengerSeats[$sIdx][$pIdx] = ['id' => '', 'price' => 0];
                            $this->refreshSeatMapStates();
                        }
                    }
                }

                // Update booking total in session
                $bookingTotal = (float) session('booking_total', 0);
                $bookingTotal -= $item['amount'];
                session(['booking_total' => $bookingTotal]);

                unset($this->summaryItems[$index]);
                $this->summaryItems = array_values($this->summaryItems);
                session(['booking_summary' => $this->summaryItems]);

                $this->calculateTotal();
            }
        }
    }

    protected function refreshSeatMapStates()
    {
        $this->parseSeatMap();

        foreach ($this->rows as &$row) {
            foreach ($row['seats'] as $letter => &$seat) {
                // Apply selected state only for CURRENT segment
                foreach ($this->passengerSeats[$this->currentSegmentIndex] ?? [] as $s) {
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

        foreach ($this->passengerSeats as $sIdx => $segmentSeats) {
            foreach ($segmentSeats as $pIdx => $seat) {
                if (!empty($seat['id'])) {
                    $summary[] = [
                        'label' => "Seat Selection S" . ($sIdx + 1) . "-P" . ($pIdx + 1) . " (" . $seat['id'] . ")",
                        'amount' => $seat['price'],
                        'removable' => true
                    ];
                }
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