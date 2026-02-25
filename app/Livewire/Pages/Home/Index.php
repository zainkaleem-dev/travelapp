<?php

namespace App\Livewire\Pages\Home;

use Livewire\Component;
use App\Http\Controllers\Api\FlightApiController;
use Illuminate\Http\Request;

class Index extends Component
{
    public $oneWayOrigin = '';
    public $oneWayDestination = '';
    public $oneWayDepartureDate = '';

    public $oneWayAdults = 1;
    public $oneWayChildren = 0;
    public $oneWayInfants = 0;

    public $oneWayTravelClass = 'Economy';

    public $oneWayRefundable = false;
    public $oneWayNonStop = false;
    public $oneWayGdsReturn = false;

    public $flightResults = null;

    // Return flight properties
    public $returnOrigin = '';
    public $returnDestination = '';
    public $returnDepartureDate = '';
    public $returnReturnDate = '';
    public $returnAdults = 1;
    public $returnChildren = 0;
    public $returnInfants = 0;
    public $returnTravelClass = 'Economy';
    public $returnRefundable = false;
    public $returnNonStop = false;
    public $returnGdsReturn = false;
    public $returnFlightResults = null;

    // Multicity properties
    public $multiCitySegments = [
        ['origin' => '', 'destination' => '', 'departureDate' => ''],
        ['origin' => '', 'destination' => '', 'departureDate' => '']
    ];
    public $multiCityAdults = 1;
    public $multiCityChildren = 0;
    public $multiCityInfants = 0;
    public $multiCityTravelClass = 'Economy';
    public $multiCityRefundable = false;
    public $multiCityNonStop = false;
    public $multiCityGdsReturn = false;
    public $multiCityFlightResults = null;
    public $activeTab = 'oneway';
    public $loading = false;
    public $searchError = null;

    public function mount()
    {
        // Test data for development
        $this->oneWayOrigin = 'LON';
        $this->oneWayDestination = 'NYC';
        $this->oneWayDepartureDate = '2026-03-01';

        $this->returnOrigin = 'LON';
        $this->returnDestination = 'NYC';
        $this->returnDepartureDate = '2026-03-01';
        $this->returnReturnDate = '2026-03-08';

        $this->multiCitySegments[0]['origin'] = 'LON';
        $this->multiCitySegments[0]['destination'] = 'NYC';
        $this->multiCitySegments[0]['departureDate'] = '2026-03-01';
        $this->multiCitySegments[1]['origin'] = 'NYC';
        $this->multiCitySegments[1]['destination'] = 'LAX';
        $this->multiCitySegments[1]['departureDate'] = '2026-03-10';
    }

    public function testSearch()
    {
        // Set test data
        $this->oneWayOrigin = 'LON';
        $this->oneWayDestination = 'NYC';
        $this->oneWayDepartureDate = '2026-03-01';
        $this->oneWayAdults = 1;

        // Call search
        $this->searchFlights();
    }

    public function testReturnSearch()
    {
        // Set test data
        $this->returnOrigin = 'LON';
        $this->returnDestination = 'NYC';
        $this->returnDepartureDate = '2026-03-01';
        $this->returnReturnDate = '2026-03-08';
        $this->returnAdults = 1;

        // Call search
        $this->searchReturnFlights();
    }

    public function testMultiCitySearch()
    {
        // Set test data
        $this->multiCitySegments[0]['origin'] = 'LON';
        $this->multiCitySegments[0]['destination'] = 'NYC';
        $this->multiCitySegments[0]['departureDate'] = '2026-03-01';
        $this->multiCitySegments[1]['origin'] = 'NYC';
        $this->multiCitySegments[1]['destination'] = 'LAX';
        $this->multiCitySegments[1]['departureDate'] = '2026-03-10';
        $this->multiCityAdults = 1;

        // Call search
        $this->searchMultiCityFlights();
    }

    public function searchFlights()
    {
        $this->activeTab = 'oneway';
        $this->loading = true;
        $this->searchError = null;

        $this->oneWayOrigin = strtoupper(trim((string) ($this->oneWayOrigin ?: 'LON')));
        $this->oneWayDestination = strtoupper(trim((string) ($this->oneWayDestination ?: 'NYC')));
        if (empty($this->oneWayDepartureDate)) {
            $this->oneWayDepartureDate = now()->addDays(5)->toDateString();
        }

        // Validate required fields
        $this->validate([
            'oneWayOrigin' => 'required|string|size:3',
            'oneWayDestination' => 'required|string|size:3',
            'oneWayDepartureDate' => 'required|date',
        ]);

        try {
            // Create a request object with the search parameters
            $request = new Request();
            $request->merge([
                'originLocationCode' => strtoupper($this->oneWayOrigin),
                'destinationLocationCode' => strtoupper($this->oneWayDestination),
                'departureDate' => $this->oneWayDepartureDate,
                'adults' => $this->oneWayAdults,
                'children' => $this->oneWayChildren,
                'infants' => $this->oneWayInfants,
                'travelClass' => strtoupper($this->oneWayTravelClass),
                'nonStop' => $this->oneWayNonStop,
                'max' => 10, // Limit results
            ]);

            // Instantiate the controller and call the method
            $controller = new FlightApiController();
            $response = $controller->searchFlights($request);

            // Handle the response properly
            if ($response->getStatusCode() === 200) {
                // For JsonResponse, getData() returns the data array
                $this->flightResults = $response->getData(true);
                if (!isset($this->flightResults['data']) || count($this->flightResults['data']) === 0) {
                    $this->searchError = 'No live flights returned. Showing demo data.';
                    $this->flightResults = $this->getDummyOneWayResults();
                }
            } else {
                // Handle error response
                $errorData = $response->getData(true);
                $this->searchError = 'Live API issue: ' . ($errorData['message'] ?? 'Unknown error') . '. Showing demo data.';
                $this->flightResults = $this->getDummyOneWayResults();
            }

            // Flash success message
            session()->flash('message', 'Flights found successfully!');

        } catch (\Exception $e) {
            // Handle errors
            $this->searchError = 'Live API failed: ' . $e->getMessage() . '. Showing demo data.';
            $this->flightResults = $this->getDummyOneWayResults();
        } finally {
            $this->loading = false;
        }
    }

    private function getDummyOneWayResults(): array
    {
        $origin = strtoupper(trim((string) ($this->oneWayOrigin ?: 'LON')));
        $destination = strtoupper(trim((string) ($this->oneWayDestination ?: 'NYC')));
        $date = $this->oneWayDepartureDate ?: now()->addDays(5)->toDateString();

        return [
            'data' => [
                [
                    'id' => 'demo-1',
                    'itineraries' => [[
                        'duration' => 'PT7H55M',
                        'segments' => [[
                            'departure' => ['iataCode' => $origin, 'at' => $date . 'T13:00:00'],
                            'arrival' => ['iataCode' => $destination, 'at' => $date . 'T15:55:00'],
                            'carrierCode' => 'A1',
                            'number' => '5951',
                        ]],
                    ]],
                    'price' => ['total' => '299.35'],
                ],
                [
                    'id' => 'demo-2',
                    'itineraries' => [[
                        'duration' => 'PT7H55M',
                        'segments' => [[
                            'departure' => ['iataCode' => $origin, 'at' => $date . 'T13:00:00'],
                            'arrival' => ['iataCode' => $destination, 'at' => $date . 'T15:55:00'],
                            'carrierCode' => 'X1',
                            'number' => '1160',
                        ]],
                    ]],
                    'price' => ['total' => '310.87'],
                ],
                [
                    'id' => 'demo-3',
                    'itineraries' => [[
                        'duration' => 'PT16H30M',
                        'segments' => [
                            [
                                'departure' => ['iataCode' => $origin, 'at' => $date . 'T10:40:00'],
                                'arrival' => ['iataCode' => 'LIS', 'at' => $date . 'T13:25:00'],
                                'carrierCode' => 'TP',
                                'number' => '1335',
                            ],
                            [
                                'departure' => ['iataCode' => 'LIS', 'at' => $date . 'T18:45:00'],
                                'arrival' => ['iataCode' => $destination, 'at' => $date . 'T22:10:00'],
                                'carrierCode' => 'TP',
                                'number' => '203',
                            ],
                        ],
                    ]],
                    'price' => ['total' => '343.40'],
                ],
            ],
            'dictionaries' => [
                'carriers' => [
                    'A1' => 'A.P.G. DISTRIBUTION SYSTEM',
                    'X1' => 'HAHN AIR TECHNOLOGIES',
                    'TP' => 'TAP PORTUGAL',
                ],
            ],
        ];
    }

    public function increaseAdults()
    {
        $this->oneWayAdults++;
    }

    public function decreaseAdults()
    {
        if ($this->oneWayAdults > 1) {
            $this->oneWayAdults--;
        }
    }

    public function increaseChildren()
    {
        $this->oneWayChildren++;
    }

    public function decreaseChildren()
    {
        if ($this->oneWayChildren > 0) {
            $this->oneWayChildren--;
        }
    }

    public function increaseInfants()
    {
        $this->oneWayInfants++;
    }

    public function decreaseInfants()
    {
        if ($this->oneWayInfants > 0) {
            $this->oneWayInfants--;
        }
    }

    // Return flight methods
    public function increaseReturnAdults()
    {
        $this->returnAdults++;
    }

    public function decreaseReturnAdults()
    {
        if ($this->returnAdults > 1) {
            $this->returnAdults--;
        }
    }

    public function increaseReturnChildren()
    {
        $this->returnChildren++;
    }

    public function decreaseReturnChildren()
    {
        if ($this->returnChildren > 0) {
            $this->returnChildren--;
        }
    }

    public function increaseReturnInfants()
    {
        $this->returnInfants++;
    }

    public function decreaseReturnInfants()
    {
        if ($this->returnInfants > 0) {
            $this->returnInfants--;
        }
    }

    public function searchReturnFlights()
    {
        $this->activeTab = 'return';
        $this->loading = true;

        // Validate required fields
        $this->validate([
            'returnOrigin' => 'required|string|size:3',
            'returnDestination' => 'required|string|size:3',
            'returnDepartureDate' => 'required|date',
            'returnReturnDate' => 'required|date',
        ]);

        try {
            // Create a request object with the search parameters
            $request = new Request();
            $request->merge([
                'originLocationCode' => strtoupper($this->returnOrigin),
                'destinationLocationCode' => strtoupper($this->returnDestination),
                'departureDate' => $this->returnDepartureDate,
                'returnDate' => $this->returnReturnDate,
                'adults' => $this->returnAdults,
                'children' => $this->returnChildren,
                'infants' => $this->returnInfants,
                'travelClass' => strtoupper($this->returnTravelClass),
                'nonStop' => $this->returnNonStop,
                'max' => 10, // Limit results
            ]);

            // Instantiate the controller and call the method
            $controller = new FlightApiController();
            $response = $controller->searchFlights($request);

            // Handle the response properly
            if ($response->getStatusCode() === 200) {
                // For JsonResponse, getData() returns the data array
                $this->returnFlightResults = $response->getData(true);
            } else {
                // Handle error response
                $errorData = $response->getData(true);
                $this->returnFlightResults = null;
                session()->flash('error', 'Failed to search return flights: ' . ($errorData['message'] ?? 'Unknown error'));
                return;
            }

            // Flash success message
            session()->flash('message', 'Return flights found successfully!');

        } catch (\Exception $e) {
            // Handle errors
            $this->returnFlightResults = null;
            session()->flash('error', 'Failed to search return flights: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    // Multicity methods
    public function addMultiCitySegment()
    {
        $this->multiCitySegments[] = ['origin' => '', 'destination' => '', 'departureDate' => ''];
    }

    public function removeMultiCitySegment($index)
    {
        if (count($this->multiCitySegments) > 2) {
            unset($this->multiCitySegments[$index]);
            $this->multiCitySegments = array_values($this->multiCitySegments);
        }
    }

    public function increaseMultiCityAdults()
    {
        $this->multiCityAdults++;
    }

    public function decreaseMultiCityAdults()
    {
        if ($this->multiCityAdults > 1) {
            $this->multiCityAdults--;
        }
    }

    public function increaseMultiCityChildren()
    {
        $this->multiCityChildren++;
    }

    public function decreaseMultiCityChildren()
    {
        if ($this->multiCityChildren > 0) {
            $this->multiCityChildren--;
        }
    }

    public function increaseMultiCityInfants()
    {
        $this->multiCityInfants++;
    }

    public function decreaseMultiCityInfants()
    {
        if ($this->multiCityInfants > 0) {
            $this->multiCityInfants--;
        }
    }

    public function searchMultiCityFlights()
    {
        $this->activeTab = 'multiCity';
        $this->loading = true;

        // Validate required fields for all segments
        $rules = [];
        foreach ($this->multiCitySegments as $index => $segment) {
            $rules["multiCitySegments.{$index}.origin"] = 'required|string|size:3';
            $rules["multiCitySegments.{$index}.destination"] = 'required|string|size:3';
            $rules["multiCitySegments.{$index}.departureDate"] = 'required|date';
        }

        $this->validate($rules);

        try {
            // For multicity, we need to make separate API calls for each segment
            // or modify the API to handle multiple segments
            // For now, let's implement a basic version that searches the first segment
            $firstSegment = $this->multiCitySegments[0];

            $request = new Request();
            $request->merge([
                'originLocationCode' => strtoupper($firstSegment['origin']),
                'destinationLocationCode' => strtoupper($firstSegment['destination']),
                'departureDate' => $firstSegment['departureDate'],
                'adults' => $this->multiCityAdults,
                'children' => $this->multiCityChildren,
                'infants' => $this->multiCityInfants,
                'travelClass' => strtoupper($this->multiCityTravelClass),
                'nonStop' => $this->multiCityNonStop,
                'max' => 10,
            ]);

            $controller = new FlightApiController();
            $response = $controller->searchFlights($request);

            // Handle the response properly
            if ($response->getStatusCode() === 200) {
                // For JsonResponse, getData() returns the data array
                $this->multiCityFlightResults = $response->getData(true);
            } else {
                // Handle error response
                $errorData = $response->getData(true);
                $this->multiCityFlightResults = null;
                session()->flash('error', 'Failed to search multicity flights: ' . ($errorData['message'] ?? 'Unknown error'));
                return;
            }

            session()->flash('message', 'Multicity flights found successfully!');

        } catch (\Exception $e) {
            $this->multiCityFlightResults = null;
            session()->flash('error', 'Failed to search multicity flights: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.pages.home.index')
            ->layout('layouts.flightworld');
    }
}
