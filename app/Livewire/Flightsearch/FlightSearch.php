<?php

namespace App\Livewire\Flightsearch;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Services\AmadeusService;
use Exception;

use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class FlightSearch extends Component
{
    // Search results from Amadeus
    public array $airportSearchResults = [];
    public string $searchType = ''; // 'returnDep', 'returnArr', 'onewayDep', 'onewayArr', 'multi.0.dep', etc.

    // ── Trip type tab ──────────────────────────────────────────────────
    public string $tripType = 'return'; // return | oneway | multi

    // ── Return fields ──────────────────────────────────────────────────
    public string $returnDep = '';
    public string $returnArr = '';
    public string $returnDepDate = '';
    public string $returnRetDate = '';
    public string $returnPax = '1 Adult';
    public string $returnClass = 'Economy Class';
    public bool $returnFlexible = false;
    public int $returnAdults = 1;
    public int $returnChildren = 0;
    public int $returnInfants = 0;

    // Airport autocomplete state (Return tab)
    public bool $showReturnDepAirports = false;
    public bool $showReturnArrAirports = false;

    // ── One-way fields ─────────────────────────────────────────────────
    public string $onewayDep = '';
    public string $onewayArr = '';
    public string $onewayDepDate = '';
    public string $onewayPax = '1 Adult';
    public string $onewayClass = 'Economy Class';
    public bool $onewayFlexible = false;
    public int $onewayAdults = 1;
    public int $onewayChildren = 0;
    public int $onewayInfants = 0;
    public bool $showOnewayDepAirports = false;
    public bool $showOnewayArrAirports = false;

    // ── Multi-city fields ──────────────────────────────────────────────
    public array $multiFlights = [
        ['dep' => '', 'arr' => '', 'date' => ''],
        ['dep' => '', 'arr' => '', 'date' => ''],
    ];
    public string $multiPax = '1 Adult';
    public string $multiClass = 'Economy Class';
    public bool $multiFlexible = false;
    public int $multiAdults = 1;
    public int $multiChildren = 0;
    public int $multiInfants = 0;
    public array $showMultiDepAirports = [false, false];
    public array $showMultiArrAirports = [false, false];

    // ── Searching state ────────────────────────────────────────────────
    public bool $searching = false;
    public string $currency = 'USD'; // PKR | USD | EUR | GBP etc.
    public array $currencies = [
        'USD' => 'US Dollar ($)',
        'PKR' => 'Pakistani Rupee (Rs)',
        'EUR' => 'Euro (€)',
        'GBP' => 'British Pound (£)',
        'AED' => 'UAE Dirham (DH)',
        'SAR' => 'Saudi Riyal (SR)',
        'TRY' => 'Turkish Lira (₺)',
    ];

    // ── Constants ─────────────────────────────────────────────────────
    const MAX_FLIGHTS = 5;

    // ── Computed helpers ───────────────────────────────────────────────
    public function getCanAddFlightProperty(): bool
    {
        return count($this->multiFlights) < self::MAX_FLIGHTS;
    }

    // ── Actions ────────────────────────────────────────────────────────
    public function switchTab(string $tab): void
    {
        $this->tripType = $tab;
    }

    public function addFlight(): void
    {
        if (count($this->multiFlights) < self::MAX_FLIGHTS) {
            $this->multiFlights[] = ['dep' => '', 'arr' => '', 'date' => ''];
            $this->showMultiDepAirports[] = false;
            $this->showMultiArrAirports[] = false;
        }
    }

    public function removeFlight(int $index): void
    {
        // First two flights are permanent
        if ($index < 2)
            return;

        array_splice($this->multiFlights, $index, 1);
        $this->multiFlights = array_values($this->multiFlights);

        if (isset($this->showMultiDepAirports[$index])) {
            array_splice($this->showMultiDepAirports, $index, 1);
            $this->showMultiDepAirports = array_values($this->showMultiDepAirports);
        }
        if (isset($this->showMultiArrAirports[$index])) {
            array_splice($this->showMultiArrAirports, $index, 1);
            $this->showMultiArrAirports = array_values($this->showMultiArrAirports);
        }
    }

    public function search(): void
    {
        $this->searching = true;

        // Validate based on active tab
        $rules = match ($this->tripType) {
            'return' => [
                'returnDep' => 'required',
                'returnArr' => 'required',
                'returnDepDate' => 'required|date',
                'returnRetDate' => 'required|date|after:returnDepDate',
            ],
            'oneway' => [
                'onewayDep' => 'required',
                'onewayArr' => 'required',
                'onewayDepDate' => 'required|date',
            ],
            'multi' => [
                'multiFlights.*.dep' => 'required',
                'multiFlights.*.arr' => 'required',
                'multiFlights.*.date' => 'required|date',
            ],
        };

        $this->validate($rules);

        // Simulate search delay — in a real app dispatch a job or redirect
        if ($this->tripType === 'return') {
            preg_match('/\(([A-Z]{3})\)/', $this->returnDep, $depMatches);
            preg_match('/\(([A-Z]{3})\)/', $this->returnArr, $arrMatches);

            $originIata = $depMatches[1] ?? '';
            $destIata = $arrMatches[1] ?? '';

            $classMap = [
                'Economy Class' => 'ECONOMY',
                'Premium Economy' => 'PREMIUM_ECONOMY',
                'Business Class' => 'BUSINESS',
                'First Class' => 'FIRST',
            ];
            $travelClassEnum = $classMap[$this->returnClass] ?? 'ECONOMY';

            session([
                'flight_search_params' => [
                    'origin' => $this->returnDep,
                    'destination' => $this->returnArr,
                    'originIata' => $originIata,
                    'destIata' => $destIata,
                    'departDate' => $this->returnDepDate,
                    'returnDate' => $this->returnRetDate,
                    'adultCount' => $this->returnAdults,
                    'childCount' => $this->returnChildren,
                    'infantCount' => $this->returnInfants,
                    'travelClass' => $this->returnClass,
                    'travelClassEnum' => $travelClassEnum,
                    'currency' => $this->currency,
                    'isMulti' => false,
                ]
            ]);

            $this->redirectRoute('flights.list');
            return;
        }
        if ($this->tripType === 'oneway') {
            preg_match('/\(([A-Z]{3})\)/', $this->onewayDep, $depMatches);
            preg_match('/\(([A-Z]{3})\)/', $this->onewayArr, $arrMatches);

            $originIata = $depMatches[1] ?? '';
            $destIata = $arrMatches[1] ?? '';

            $classMap = [
                'Economy Class' => 'ECONOMY',
                'Premium Economy' => 'PREMIUM_ECONOMY',
                'Business Class' => 'BUSINESS',
                'First Class' => 'FIRST',
            ];
            $travelClassEnum = $classMap[$this->onewayClass] ?? 'ECONOMY';

            session([
                'flight_search_params' => [
                    'origin' => $this->onewayDep,
                    'destination' => $this->onewayArr,
                    'originIata' => $originIata,
                    'destIata' => $destIata,
                    'departDate' => $this->onewayDepDate,
                    'returnDate' => '',
                    'adultCount' => $this->onewayAdults,
                    'childCount' => $this->onewayChildren,
                    'infantCount' => $this->onewayInfants,
                    'travelClass' => $this->onewayClass,
                    'travelClassEnum' => $travelClassEnum,
                    'currency' => $this->currency,
                    'isMulti' => false,
                ]
            ]);

            $this->redirectRoute('flights.list');
            return;
        }

        if ($this->tripType === 'multi') {
            $segments = [];
            foreach ($this->multiFlights as $flight) {
                preg_match('/\(([A-Z]{3})\)/', $flight['dep'], $depMatches);
                preg_match('/\(([A-Z]{3})\)/', $flight['arr'], $arrMatches);

                $segments[] = [
                    'originIata' => $depMatches[1] ?? '',
                    'destIata' => $arrMatches[1] ?? '',
                    'date' => $flight['date'],
                    'origin' => $flight['dep'],
                    'destination' => $flight['arr'],
                ];
            }

            $classMap = [
                'Economy Class' => 'ECONOMY',
                'Premium Economy' => 'PREMIUM_ECONOMY',
                'Business Class' => 'BUSINESS',
                'First Class' => 'FIRST',
            ];
            $travelClassEnum = $classMap[$this->multiClass] ?? 'ECONOMY';

            session([
                'flight_search_params' => [
                    'isMulti' => true,
                    'segments' => $segments,
                    'adultCount' => $this->multiAdults,
                    'childCount' => $this->multiChildren,
                    'infantCount' => $this->multiInfants,
                    'travelClass' => $this->multiClass,
                    'travelClassEnum' => $travelClassEnum,
                    'currency' => $this->currency,
                ]
            ]);

            $this->redirectRoute('flights.list');
            return;
        }

        // Fallback or unexpected state
        return;
    }

    public function doneSearching(): void
    {
        $this->searching = false;
    }

    public function fetchAirports(string $query, string $type = ''): void
    {
        $this->searchType = $type;
        \Log::info("fetchAirports called with query: '$query' for type: '$type'");
        $q = trim(mb_strtolower($query));
        if ($q === '' || strlen($q) < 2) {
            $this->airportSearchResults = [];
            return;
        }

        try {
            $service = app(AmadeusService::class);
            $response = $service->searchLocations($q);

            if (isset($response['data']) && is_array($response['data'])) {
                // Map Amadeus response to a simple array for the dropdown
                $results = array_map(function ($location) {
                    $cityName = $location['address']['cityName'] ?? '';
                    $countryName = $location['address']['countryName'] ?? '';
                    $airportName = $location['name'] ?? '';
                    $iataCode = $location['iataCode'] ?? '';

                    $display = "{$cityName} ({$iataCode})";
                    if ($airportName && stripos($airportName, $cityName) === false) {
                        $display .= " - {$airportName}";
                    }
                    if ($countryName) {
                        $display .= ", {$countryName}";
                    }

                    return [
                        'code' => $iataCode,
                        'city' => $cityName,
                        'country' => $countryName,
                        'airport' => $airportName,
                        'display' => $display,
                    ];
                }, array_slice($response['data'], 0, 8)); // Limit to top 8 results

                \Log::info("fetchAirports found " . count($results) . " results for '$q'");
                if (count($results) > 0) {
                    \Log::info("Sample result: " . $results[0]['display']);
                }
                $this->airportSearchResults = $results;
            } else {
                \Log::info("fetchAirports no results for '$q'");
                $this->airportSearchResults = [];
            }
        } catch (\Exception $e) {
            \Log::error('Amadeus Airport Search Error: ' . $e->getMessage());
            $this->airportSearchResults = [];
        }
    }

    public function updatedReturnDep(): void
    {
        if (str_contains($this->returnDep, ' (')) {
            return;
        }
        $this->showReturnDepAirports = true;
        $this->fetchAirports($this->returnDep, 'returnDep');
    }

    public function updatedReturnArr(): void
    {
        if (str_contains($this->returnArr, ' (')) {
            return;
        }
        $this->showReturnArrAirports = true;
        $this->fetchAirports($this->returnArr, 'returnArr');
    }

    public function selectReturnDepAirport(string $display): void
    {
        \Log::info("selectReturnDepAirport called with: " . $display);
        $this->returnDep = $display;
        $this->showReturnDepAirports = false;
    }

    public function selectReturnArrAirport(string $display): void
    {
        \Log::info("selectReturnArrAirport called with: " . $display);
        $this->returnArr = $display;
        $this->showReturnArrAirports = false;
    }

    public function closeAirportDropdowns(): void
    {
        $this->showReturnDepAirports = false;
        $this->showReturnArrAirports = false;
        $this->showOnewayDepAirports = false;
        $this->showOnewayArrAirports = false;
    }

    public function updatedOnewayDep(): void
    {
        if (str_contains($this->onewayDep, ' (')) {
            return;
        }
        $this->showOnewayDepAirports = true;
        $this->fetchAirports($this->onewayDep, 'onewayDep');
    }

    public function updatedOnewayArr(): void
    {
        if (str_contains($this->onewayArr, ' (')) {
            return;
        }
        $this->showOnewayArrAirports = true;
        $this->fetchAirports($this->onewayArr, 'onewayArr');
    }

    public function selectOnewayDepAirport(string $display): void
    {
        $this->onewayDep = $display;
        $this->showOnewayDepAirports = false;
    }

    public function selectOnewayArrAirport(string $display): void
    {
        $this->onewayArr = $display;
        $this->showOnewayArrAirports = false;
    }

    public function selectMultiDepAirport(int $index, string $display): void
    {
        \Log::info("selectMultiDepAirport called for index $index with: " . $display);
        if (!isset($this->multiFlights[$index])) {
            return;
        }
        $this->multiFlights[$index]['dep'] = $display;
        $this->showMultiDepAirports[$index] = false;
    }

    public function selectMultiArrAirport(int $index, string $display): void
    {
        \Log::info("selectMultiArrAirport called for index $index with: " . $display);
        if (!isset($this->multiFlights[$index])) {
            return;
        }
        $this->multiFlights[$index]['arr'] = $display;
        $this->showMultiArrAirports[$index] = false;
    }

    public function updated($propertyName): void
    {
        // Intercept updates to the multiFlights array (e.g. "multiFlights.0.dep")
        if (str_starts_with($propertyName, 'multiFlights.')) {
            $parts = explode('.', $propertyName);
            if (count($parts) === 3) {
                // $parts[0] = 'multiFlights', $parts[1] = index, $parts[2] = 'dep' or 'arr'
                $index = $parts[1];
                $field = $parts[2];
                $value = $this->multiFlights[$index][$field] ?? '';

                // Only search if the user is typing (not a final selection which has "City (CODE)")
                if (!str_contains($value, ' (')) {
                    if ($field === 'dep') {
                        $this->showMultiDepAirports[$index] = true;
                        $this->fetchAirports($value, "multi.$index.dep");
                    } else if ($field === 'arr') {
                        $this->showMultiArrAirports[$index] = true;
                        $this->fetchAirports($value, "multi.$index.arr");
                    }
                }
            }
        }
    }

    public function paxSummary(int $adults, int $children, int $infants): string
    {
        $parts = [];

        if ($adults > 0) {
            $parts[] = $adults . ' ' . ($adults === 1 ? 'Adult' : 'Adults');
        }
        if ($children > 0) {
            $parts[] = $children . ' ' . ($children === 1 ? 'Child' : 'Children');
        }
        if ($infants > 0) {
            $parts[] = $infants . ' ' . ($infants === 1 ? 'Infant' : 'Infants');
        }

        return $parts ? implode(', ', $parts) : '0 Passengers';
    }

    public function incrementReturnPax(string $type): void
    {
        $total = $this->returnAdults + $this->returnChildren + $this->returnInfants;
        if ($total >= 9) {
            return;
        }

        if ($type === 'adult') {
            $this->returnAdults++;
        } elseif ($type === 'child') {
            $this->returnChildren++;
        } elseif ($type === 'infant') {
            if ($this->returnInfants < $this->returnAdults) {
                $this->returnInfants++;
            }
        }

        $this->returnPax = $this->paxSummary($this->returnAdults, $this->returnChildren, $this->returnInfants);
    }

    public function decrementReturnPax(string $type): void
    {
        if ($type === 'adult') {
            if ($this->returnAdults <= 1) {
                return;
            }
            $this->returnAdults--;
            if ($this->returnInfants > $this->returnAdults) {
                $this->returnInfants = $this->returnAdults;
            }
        } elseif ($type === 'child') {
            if ($this->returnChildren <= 0) {
                return;
            }
            $this->returnChildren--;
        } elseif ($type === 'infant') {
            if ($this->returnInfants <= 0) {
                return;
            }
            $this->returnInfants--;
        }

        $this->returnPax = $this->paxSummary($this->returnAdults, $this->returnChildren, $this->returnInfants);
    }

    public function incrementOnewayPax(string $type): void
    {
        $total = $this->onewayAdults + $this->onewayChildren + $this->onewayInfants;
        if ($total >= 9) {
            return;
        }

        if ($type === 'adult') {
            $this->onewayAdults++;
        } elseif ($type === 'child') {
            $this->onewayChildren++;
        } elseif ($type === 'infant') {
            if ($this->onewayInfants < $this->onewayAdults) {
                $this->onewayInfants++;
            }
        }

        $this->onewayPax = $this->paxSummary($this->onewayAdults, $this->onewayChildren, $this->onewayInfants);
    }

    public function decrementOnewayPax(string $type): void
    {
        if ($type === 'adult') {
            if ($this->onewayAdults <= 1) {
                return;
            }
            $this->onewayAdults--;
            if ($this->onewayInfants > $this->onewayAdults) {
                $this->onewayInfants = $this->onewayAdults;
            }
        } elseif ($type === 'child') {
            if ($this->onewayChildren <= 0) {
                return;
            }
            $this->onewayChildren--;
        } elseif ($type === 'infant') {
            if ($this->onewayInfants <= 0) {
                return;
            }
            $this->onewayInfants--;
        }

        $this->onewayPax = $this->paxSummary($this->onewayAdults, $this->onewayChildren, $this->onewayInfants);
    }

    public function incrementMultiPax(string $type): void
    {
        $total = $this->multiAdults + $this->multiChildren + $this->multiInfants;
        if ($total >= 9) {
            return;
        }

        if ($type === 'adult') {
            $this->multiAdults++;
        } elseif ($type === 'child') {
            $this->multiChildren++;
        } elseif ($type === 'infant') {
            if ($this->multiInfants < $this->multiAdults) {
                $this->multiInfants++;
            }
        }

        $this->multiPax = $this->paxSummary($this->multiAdults, $this->multiChildren, $this->multiInfants);
    }

    public function decrementMultiPax(string $type): void
    {
        if ($type === 'adult') {
            if ($this->multiAdults <= 1) {
                return;
            }
            $this->multiAdults--;
            if ($this->multiInfants > $this->multiAdults) {
                $this->multiInfants = $this->multiAdults;
            }
        } elseif ($type === 'child') {
            if ($this->multiChildren <= 0) {
                return;
            }
            $this->multiChildren--;
        } elseif ($type === 'infant') {
            if ($this->multiInfants <= 0) {
                return;
            }
            $this->multiInfants--;
        }

        $this->multiPax = $this->paxSummary($this->multiAdults, $this->multiChildren, $this->multiInfants);
    }

    public function render()
    {
        return view('livewire.flightsearch.flight-search');
    }

}
