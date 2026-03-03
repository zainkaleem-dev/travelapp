<?php

namespace App\Livewire\Flightslist;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\FlightApiController;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class FlightList extends Component
{
    // ─── Search Form ──────────────────────────────────────────────
    #[Url]
    public string $origin = '';
    #[Url]
    public string $originIata = '';
    #[Url]
    public string $destination = '';
    #[Url]
    public string $destIata = '';
    #[Url]
    public string $departDate = '';
    #[Url]
    public string $returnDate = '';

    #[Url]
    public bool $isMulti = false;
    #[Url]
    public array $segments = [];

    public int $passengers = 1;

    // Autocomplete dropdown toggles
    public bool $showOriginAirports = false;
    public bool $showDestinationAirports = false;

    // Passenger breakdown for UI (kept in sync with $passengers)
    #[Url]
    public int $adultCount = 1;
    #[Url]
    public int $childCount = 0;
    #[Url]
    public int $infantCount = 0;

    #[Url]
    public string $travelClass = 'Economy Class';

    #[Url]
    public string $travelClassEnum = 'ECONOMY';

    // ─── Filters ──────────────────────────────────────────────────
    public int $priceMin = 100;
    public int $priceMax = 500000;
    public array $stops = ['any'];
    public array $airlines = ['any'];
    public array $departTimes = ['any'];

    // ─── Sorting / Date ──────────────────────────────────────────
    public string $sortTab = 'cheap';
    public string $selectedDate = '2026-07-14';

    // ─── Date rail (7 days around selected date) ──────────────────
    public array $dateRail = [];

    public function fetchDateRailPrices()
    {
        if ($this->isMulti || empty($this->departDate)) {
            return;
        }

        // Sync selectedDate with current departDate if not already set
        if (empty($this->selectedDate) || $this->selectedDate === '2026-07-14') {
            $this->selectedDate = $this->departDate;
        }

        // 1. Generate the 7 days (+/- 3 days from departDate)
        $centerDate = new \DateTime($this->departDate);
        $startDate = (clone $centerDate)->modify('-3 days');
        $endDate = (clone $centerDate)->modify('+3 days');

        $this->dateRail = [];
        for ($i = 0; $i < 7; $i++) {
            $current = (clone $startDate)->modify("+$i days");
            $this->dateRail[] = [
                'date' => $current->format('Y-m-d'),
                'label' => $current->format('D d.m'),
                'price' => null, // Will populate via API
            ];
        }

        // 2. Fetch from Amadeus
        if (empty($this->originIata) || empty($this->destIata))
            return;

        try {
            $amadeus = app(\App\Services\AmadeusService::class);
            // We pass a range to get multiple cheapest dates at once
            $response = $amadeus->searchFlightDates([
                'origin' => $this->originIata,
                'destination' => $this->destIata,
                'departureDate' => $startDate->format('Y-m-d') . ',' . $endDate->format('Y-m-d'),
            ]);

            if (isset($response['data']) && is_array($response['data'])) {
                foreach ($response['data'] as $offer) {
                    $offerDate = $offer['departureDate'] ?? null;
                    $offerPrice = $offer['price']['total'] ?? null;

                    if ($offerDate && $offerPrice) {
                        foreach ($this->dateRail as &$railItem) {
                            if ($railItem['date'] === $offerDate) {
                                // Only update if it's the first one found or cheaper
                                if ($railItem['price'] === null || (float) $offerPrice < $railItem['price']) {
                                    $railItem['price'] = (float) $offerPrice;
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Error fetching prices, rail remains with nulls/-
        }
    }

    // ─── Raw flight data ──────────────────────────────────────────
    public ?string $errorMessage = null;
    public array $allFlights = [];

    // Phase 4: Branded Fares / Details
    public array $fareDetails = [];
    public array $loadingFares = [];

    // ─── Computed: filtered + sorted flights ──────────────────────
    #[Computed]
    public function flights(): array
    {
        $results = array_filter($this->allFlights, function ($f) {
            // Price range
            if ($f['price'] < $this->priceMin || $f['price'] > $this->priceMax) {
                return false;
            }
            // Airlines filter
            if (!in_array('any', $this->airlines) && !in_array(strtolower($f['airline']), $this->airlines)) {
                return false;
            }
            // Stops filter
            if (!in_array('any', $this->stops)) {
                $isDirect = strtolower($f['stops']) === 'direct';
                if (in_array('direct', $this->stops) && !$isDirect)
                    return false;
                if (in_array('1stop', $this->stops) && $isDirect)
                    return false;
            }
            // Refundable filter (for the tab)
            if ($this->sortTab === 'refundable' && !$f['refundable']) {
                return false;
            }
            return true;
        });

        // Numerical Sorts
        switch ($this->sortTab) {
            case 'cheap':
                usort($results, fn($a, $b) => $a['price'] <=> $b['price']);
                break;
            case 'fastest':
                usort($results, fn($a, $b) => $a['durationMinutes'] <=> $b['durationMinutes']);
                break;
            case 'early':
                usort($results, fn($a, $b) => $a['departureTimestamp'] <=> $b['departureTimestamp']);
                break;
            case 'late':
                usort($results, fn($a, $b) => $b['departureTimestamp'] <=> $a['departureTimestamp']);
                break;
            case 'best':
                // Smart Sort: Weighted score of price and duration
                // We'll normalize by finding the min values in current window
                if (!empty($results)) {
                    $minPrice = min(array_column($results, 'price')) ?: 1;
                    $minDur = min(array_column($results, 'durationMinutes')) ?: 1;

                    usort($results, function ($a, $b) use ($minPrice, $minDur) {
                        $scoreA = ($a['price'] / $minPrice) + ($a['durationMinutes'] / $minDur);
                        $scoreB = ($b['price'] / $minPrice) + ($b['durationMinutes'] / $minDur);
                        return $scoreA <=> $scoreB;
                    });
                }
                break;
        }

        return array_values($results);
    }

    #[Computed]
    public function availableAirlines(): array
    {
        return collect($this->allFlights)
            ->pluck('airline')
            ->unique()
            ->values()
            ->toArray();
    }

    // ─── Actions ──────────────────────────────────────────────────
    public function search(): void
    {
        // In a real app: dispatch a job or query the DB/API
        // For now we just re-render with current filters
        $this->dispatch('searched');
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->departDate = $date;
        $this->fetchDateRailPrices();
        $this->loadFlights();
    }

    public function setSort(string $tab): void
    {
        $this->sortTab = $tab;
    }

    public function clearFilters(): void
    {
        $this->priceMin = 100;
        $this->priceMax = 500000;
        $this->stops = ['any'];
        $this->airlines = ['any'];
        $this->departTimes = ['any'];
    }

    // ─── Airport Search ───────────────────────────────────────────
    public array $airportSearchResults = [];

    public function fetchAirports(string $query): array
    {
        $q = trim(mb_strtolower($query));
        if ($q === '' || strlen($q) < 2) {
            return [];
        }

        try {
            $service = app(\App\Services\AmadeusService::class);
            $response = $service->searchLocations($q);

            if (isset($response['data']) && is_array($response['data'])) {
                return array_map(function ($location) {
                    $cityName = $location['address']['cityName'] ?? '';
                    $countryName = $location['address']['countryName'] ?? '';
                    $airportName = $location['name'] ?? '';
                    $iataCode = $location['iataCode'] ?? '';

                    return [
                        'code' => $iataCode,
                        'city' => $cityName,
                        'country' => $countryName,
                        'airport' => $airportName,
                    ];
                }, array_slice($response['data'], 0, 8)); // Top 8 results
            }
        } catch (\Exception $e) {
            // Silently fail or return empty on API timeout
        }
        return [];
    }

    public function filteredAirports(string $query): array
    {
        // For standard UI render pass, we fetch via API
        return $this->fetchAirports($query);
    }

    public function updatedOrigin(): void
    {
        $this->showOriginAirports = true;
    }

    public function updatedDestination(): void
    {
        $this->showDestinationAirports = true;
    }

    public function selectOriginAirport(string $display): void
    {
        $this->origin = $display;
        $this->showOriginAirports = false;
    }

    public function selectDestinationAirport(string $display): void
    {
        $this->destination = $display;
        $this->showDestinationAirports = false;
    }

    protected function syncPassengersTotal(): void
    {
        $this->passengers = $this->adultCount + $this->childCount + $this->infantCount;
    }

    public function incrementPassengerType(string $type): void
    {
        $total = $this->adultCount + $this->childCount + $this->infantCount;
        if ($total >= 9) {
            return;
        }

        if ($type === 'adult') {
            $this->adultCount++;
        } elseif ($type === 'child') {
            $this->childCount++;
        } elseif ($type === 'infant') {
            if ($this->infantCount < $this->adultCount) {
                $this->infantCount++;
            }
        }

        $this->syncPassengersTotal();
    }

    public function decrementPassengerType(string $type): void
    {
        if ($type === 'adult') {
            if ($this->adultCount <= 1) {
                return;
            }
            $this->adultCount--;
            if ($this->infantCount > $this->adultCount) {
                $this->infantCount = $this->adultCount;
            }
        } elseif ($type === 'child') {
            if ($this->childCount <= 0) {
                return;
            }
        } elseif ($type === 'infant') {
            if ($this->infantCount <= 0) {
                return;
            }
            $this->infantCount--;
        }

        $this->syncPassengersTotal();
    }

    public function mount()
    {
        // ── Fast fallback for direct URL visits without params ──
        if (!$this->isMulti && empty($this->origin) && empty($this->originIata)) {
            $this->origin = 'Düsseldorf Airport (DUS)';
            $this->originIata = 'DUS';
            $this->destination = 'Istanbul Airport (IST)';
            $this->destIata = 'IST';
            $this->departDate = now()->addDays(7)->format('Y-m-d');
            $this->returnDate = now()->addDays(14)->format('Y-m-d');
        }

        $this->syncPassengersTotal();
        $this->fetchDateRailPrices();
        $this->loadFlights();
    }

    public function loadFlights(): void
    {
        $amadeusService = app(\App\Services\AmadeusService::class);
        $controller = new FlightApiController($amadeusService);

        if ($this->isMulti && !empty($this->segments)) {
            $originDestinations = [];
            foreach ($this->segments as $index => $segment) {
                $originDestinations[] = [
                    'id' => (string) ($index + 1),
                    'originLocationCode' => $segment['originIata'],
                    'destinationLocationCode' => $segment['destIata'],
                    'departureDateTimeRange' => [
                        'date' => $segment['date'],
                    ],
                ];
            }

            $travelers = [];
            for ($i = 0; $i < $this->adultCount; $i++) {
                $travelers[] = ['id' => (string) (count($travelers) + 1), 'travelerType' => 'ADULT'];
            }
            for ($i = 0; $i < $this->childCount; $i++) {
                $travelers[] = ['id' => (string) (count($travelers) + 1), 'travelerType' => 'CHILD'];
            }
            for ($i = 0; $i < $this->infantCount; $i++) {
                $travelers[] = ['id' => (string) (count($travelers) + 1), 'travelerType' => 'HELD_INFANT'];
            }

            $request = new Request();
            $request->merge([
                'originDestinations' => $originDestinations,
                'travelers' => $travelers,
                'sources' => ['GDS'],
                'searchCriteria' => [
                    'flightFilters' => [
                        'cabinRestrictions' => [
                            [
                                'cabin' => $this->travelClassEnum,
                                'coverage' => 'MOST_SEGMENTS',
                                'originDestinationIds' => array_map(fn($od) => $od['id'], $originDestinations),
                            ]
                        ]
                    ]
                ]
            ]);

            $response = $controller->searchFlightsPost($request);
        } else {
            // 1. Prepare a Laravel request mimicking what the frontend API would send
            $request = new Request();
            $request->merge([
                'originLocationCode' => $this->originIata,
                'destinationLocationCode' => $this->destIata,
                'departureDate' => $this->departDate,
                'returnDate' => $this->returnDate,
                'adults' => $this->adultCount,
                'children' => $this->childCount,
                'infants' => $this->infantCount,
                'travelClass' => $this->travelClassEnum,
            ]);

            $response = $controller->searchFlights($request);
        }

        $statusCode = $response->getStatusCode();
        $data = json_decode($response->getContent(), true);

        // 3. Map the complex AMADEUS JSON down into the simple array structure the UI expects
        if ($statusCode === 200 && isset($data['data'])) {
            $mappedFlights = [];
            $dictionaries = $data['dictionaries'] ?? [];

            foreach ($data['data'] as $index => $offer) {
                // For simplified multi-city view, we use the first itinerary's first segment for departure 
                // and the last itinerary's last segment for arrival
                $itineraries = $offer['itineraries'];
                $firstItinerary = $itineraries[0] ?? null;
                $lastItinerary = end($itineraries);

                $firstSegment = $firstItinerary['segments'][0] ?? null;
                $lastSegment = end($lastItinerary['segments']);

                if (!$firstSegment)
                    continue;

                $carrierCode = $firstSegment['carrierCode'];
                $airlineName = $dictionaries['carriers'][$carrierCode] ?? $carrierCode;

                $depTime = date('H:i', strtotime($firstSegment['departure']['at']));
                $arrTime = date('H:i', strtotime($lastSegment['arrival']['at']));

                // Total duration across all itineraries
                $totalDurationMinutes = 0;
                foreach ($itineraries as $itin) {
                    $durationRaw = $itin['duration'] ?? '';
                    if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $durationRaw, $matches)) {
                        $hours = (int) ($matches[1] ?? 0);
                        $minutes = (int) ($matches[2] ?? 0);
                        $totalDurationMinutes += ($hours * 60) + $minutes;
                    }
                }

                $h = floor($totalDurationMinutes / 60);
                $m = $totalDurationMinutes % 60;
                $duration = "{$h}h {$m}m";

                $price = (float) ($offer['price']['total'] ?? 0);

                $stopsCount = 0;
                foreach ($itineraries as $itin) {
                    $stopsCount += count($itin['segments']) - 1;
                }
                $stopsLabel = $this->isMulti ? "Multi-city" : ($stopsCount === 0 ? 'Direct' : $stopsCount . ' Stop' . ($stopsCount > 1 ? 's' : ''));

                // Calculate first departure timestamp for sorting
                $departureTimestamp = strtotime($firstSegment['departure']['at']);
                $refundable = !($offer['pricingOptions']['noRestrictionFare'] ?? false);

                $mappedFlights[] = [
                    'id' => $offer['id'],
                    'airline' => $airlineName,
                    'airlineCode' => $carrierCode,
                    'airlineColor' => 'bg-emerald-700',
                    'dep' => $depTime,
                    'arr' => $arrTime,
                    'depCity' => $this->isMulti ? ($this->segments[0]['origin'] ?? 'Multi-city') : $this->origin,
                    'depAirport' => $firstSegment['departure']['iataCode'],
                    'arrCity' => $this->isMulti ? (end($this->segments)['destination'] ?? 'Multi-city') : $this->destination,
                    'arrAirport' => $lastSegment['arrival']['iataCode'],
                    'duration' => $duration,
                    'durationMinutes' => $totalDurationMinutes,
                    'departureTimestamp' => $departureTimestamp,
                    'stops' => $stopsLabel,
                    'price' => $price,
                    'oldPrice' => null,
                    'badge' => ($index === 0) ? 'Cheapest' : null,
                    'badgeClass' => ($index === 0) ? 'cheapest-badge' : '',
                    'btnClass' => 'bg-blue-600 hover:bg-blue-700',
                    'bgClass' => '',
                    'note' => null,
                    'refundable' => $refundable,
                    'rawOffer' => $offer,
                ];
            }

            // Replace the hardcoded data with the live data!
            $this->allFlights = $mappedFlights;

            // Handle scenario where request returned 200, but array holds 0 items.
            if (empty($mappedFlights)) {
                $this->errorMessage = 'No flights available for this route on the selected date.';
            }

        } else {
            // Write to error message property
            $this->allFlights = [];

            if (isset($data['error'])) {
                $this->errorMessage = $data['error']; // Controller passed it via proxy
            } elseif (isset($data['errors']) && is_array($data['errors'])) {
                $this->errorMessage = $data['errors'][0]['detail'] ?? 'Amadeus API returned an error processing your request.';
            } else {
                $this->errorMessage = 'We could not fetch flights at this time. Please try again later.';
            }
        }
    }

    public function loadFareDetails($id): void
    {
        // Avoid redundant calls
        if (isset($this->fareDetails[$id]))
            return;

        $this->loadingFares[$id] = true;

        // Find the flight in our current list
        $flight = collect($this->allFlights)->firstWhere('id', $id);

        if (!$flight || !isset($flight['rawOffer'])) {
            $this->loadingFares[$id] = false;
            return;
        }

        try {
            $amadeus = app(\App\Services\AmadeusService::class);
            $response = $amadeus->upsellFlightOffers($flight['rawOffer']);

            if (isset($response['data']) && !empty($response['data'])) {
                // For simplicity, we just store the whole upsell response for this flight ID
                $this->fareDetails[$id] = $response['data'];
            }
        } catch (\Exception $e) {
            // Error handling
        }

        $this->loadingFares[$id] = false;
    }

    public function selectFlight($id): void
    {
        // Find the flight in our current list
        $flight = collect($this->allFlights)->firstWhere('id', $id);

        if (!$flight) {
            return;
        }

        // Store selection and search context in session for the next step
        session([
            'selected_flight' => $flight,
            'search_params' => [
                'isMulti' => $this->isMulti,
                'segments' => $this->segments,
                'origin' => $this->origin,
                'destination' => $this->destination,
                'originIata' => $this->originIata,
                'destIata' => $this->destIata,
                'departDate' => $this->departDate,
                'returnDate' => $this->returnDate,
                'passengers' => [
                    'adults' => $this->adultCount,
                    'children' => $this->childCount,
                    'infants' => $this->infantCount,
                ],
                'travelClass' => $this->travelClass,
                'travelClassEnum' => $this->travelClassEnum,
            ]
        ]);

        $this->redirect(route('passenger.details'), navigate: true);
    }

    public function render()
    {
        return view('livewire.flightslist.flight-list')
            ->layout('layouts.flight');
    }

}
