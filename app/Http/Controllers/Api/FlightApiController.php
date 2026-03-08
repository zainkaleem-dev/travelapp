<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

use App\Services\AmadeusService;

class FlightApiController extends Controller
{
    protected AmadeusService $amadeusService;

    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }



    // public function searchFlights(Request $request)
    // {
    //     $validated = $request->validate([
    //         'originLocationCode' => 'required|string|size:3',
    //         'destinationLocationCode' => 'required|string|size:3',
    //         'departureDate' => 'required|date_format:Y-m-d|after_or_equal:today',
    //         'returnDate' => 'nullable|date_format:Y-m-d|after_or_equal:departureDate',
    //         'adults' => 'required|integer|min:1|max:9',
    //         'children' => 'nullable|integer|min:0|max:9',
    //         'infants' => 'nullable|integer|min:0|max:9',
    //         'travelClass' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
    //         'nonStop' => 'nullable|boolean',
    //         'currencyCode' => 'nullable|string|size:3',
    //         'max' => 'nullable|integer|min:1|max:250',
    //     ]);

    //     try {
    //         $data = $this->amadeusService->searchFlights($validated);
    //         return response()->json($data);
    //     } catch (\Throwable $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function searchFlightsPost(Request $request)
    {
        $validated = $request->validate([
            'originDestinations' => 'required|array|min:1',
            'travelers' => 'required|array|min:1',
            'sources' => 'required|array|min:1',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        try {
            $data = $this->amadeusService->searchFlightsAdvanced($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bookFlight(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'flightOffers' => 'required|array|min:1',
            'travelers' => 'required|array|min:1',
            'remarks' => 'nullable|array',
            'ticketingAgreement' => 'nullable|array',
            'contacts' => 'nullable|array',
        ]);

        // 2️⃣ Build the payload
        $payload = [
            'data' => [
                'type' => 'flight-order',
                'flightOffers' => $validated['flightOffers'],
                'travelers' => $validated['travelers'],
            ],
        ];

        if (!empty($validated['remarks']))
            $payload['data']['remarks'] = $validated['remarks'];
        if (!empty($validated['ticketingAgreement']))
            $payload['data']['ticketingAgreement'] = $validated['ticketingAgreement'];
        if (!empty($validated['contacts']))
            $payload['data']['contacts'] = $validated['contacts'];

        try {
            $data = $this->amadeusService->bookFlight($payload);
            return response()->json($data, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function priceFlightOffer(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'flightOffers' => 'required|array|min:1',
            // Simplified validation for brevity as service handles the payload
        ]);

        try {
            $data = $this->amadeusService->priceFlightOffer($validated['flightOffers']);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFlightOrder(string $orderId)
    {
        if (empty($orderId)) {
            return response()->json(['error' => 'Order ID is required'], 400);
        }

        try {
            $data = $this->amadeusService->getFlightOrder($orderId);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteFlightOrder(string $orderId)
    {
        if (empty($orderId)) {
            return response()->json(['error' => 'Order ID is required'], 400);
        }

        try {
            $data = $this->amadeusService->deleteFlightOrder($orderId);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSeatMapByOrderId(Request $request)
    {
        $validated = $request->validate([
            'flight-orderId' => 'required|string',
        ]);

        try {
            $data = $this->amadeusService->getSeatMapByOrderId($validated['flight-orderId']);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSeatMapByFlightOffer(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|array|min:1',
        ]);

        try {
            // Service method accepts many but typically we send one flight offer
            $data = $this->amadeusService->getSeatMapByFlightOffer($validated['data'][0]);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function upsellFlightOffer(Request $request)
    {
        $validated = $request->validate([
            'flightOffers' => 'required|array|min:1',
            // Simplified validation as service handles it
        ]);

        try {
            $data = $this->amadeusService->upsellFlightOffers($validated['flightOffers'][0]);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getItineraryPriceMetrics(Request $request)
    {
        $validated = $request->validate([
            'originIataCode' => 'required|string|size:3',
            'destinationIataCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m',
            'currencyCode' => 'nullable|string|size:3',
            'oneWay' => 'nullable|boolean',
        ]);

        try {
            $data = $this->amadeusService->getItineraryPriceMetrics($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function predictFlightChoice(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|array|min:1',
            'dictionaries' => 'nullable|array',
            'meta' => 'nullable|array',
        ]);

        try {
            $data = $this->amadeusService->predictFlightChoice($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFlightDestinations(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|size:3',
            'departureDate' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'oneWay' => 'nullable|boolean',
            'duration' => 'nullable|string',
            'nonStop' => 'nullable|boolean',
            'maxPrice' => 'nullable|integer|min:1',
            'viewBy' => 'nullable|in:COUNTRY,DATE,DESTINATION,DURATION,WEEK',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        try {
            $data = $this->amadeusService->getFlightDestinations($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFlightDates(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departureDate' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'oneWay' => 'nullable|boolean',
            'duration' => 'nullable|string',
            'nonStop' => 'nullable|boolean',
            'maxPrice' => 'nullable|integer|min:1',
            'viewBy' => 'nullable|in:DATE,DURATION,WEEK',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        try {
            $data = $this->amadeusService->searchFlightDates($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getFlightAvailabilities(Request $request)
    {
        $validated = $request->validate([
            'originDestinations' => 'required|array|min:1',
            'travelers' => 'required|array|min:1',
            'sources' => 'required|array|min:1',
        ]);

        try {
            $data = $this->amadeusService->getFlightAvailabilities($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRecommendedLocations(Request $request)
    {
        $validated = $request->validate([
            'cityCodes' => 'required|string',
            'travelerCountryCode' => 'nullable|string|size:2',
            'destinationCountryCodes' => 'nullable|string',
        ]);

        try {
            $data = $this->amadeusService->getRecommendedLocations($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFlightSchedule(Request $request)
    {
        $validated = $request->validate([
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string|max:4',
            'scheduledDepartureDate' => 'required|date_format:Y-m-d',
        ]);

        try {
            $data = $this->amadeusService->getFlightSchedule($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function predictFlightDelay(Request $request)
    {
        $validated = $request->validate([
            'originLocationCode' => 'required|string|size:3',
            'destinationLocationCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m-d',
            'departureTime' => 'required|date_format:H:i:s',
            'arrivalDate' => 'required|date_format:Y-m-d',
            'arrivalTime' => 'required|date_format:H:i:s',
            'aircraftCode' => 'required|string|max:4',
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string|max:4',
            'duration' => 'required|string',
        ]);

        try {
            $data = $this->amadeusService->predictFlightDelay($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function predictAirportOnTime(Request $request)
    {
        $validated = $request->validate([
            'airportCode' => 'required|string|size:3',
            'date' => 'required|date_format:Y-m-d',
        ]);

        try {
            $data = $this->amadeusService->predictAirportOnTime($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCheckinLinks(Request $request)
    {
        $validated = $request->validate([
            'airlineCode' => 'required|string|size:2',
            'language' => 'nullable|string|max:5',
        ]);

        try {
            $data = $this->amadeusService->getCheckinLinks($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function getAirlines(Request $request)
    {
        $validated = $request->validate([
            'airlineCodes' => 'required|string',
        ]);

        try {
            $data = $this->amadeusService->getAirlines($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAirlineDestinations(Request $request)
    {
        $validated = $request->validate([
            'airlineCode' => 'required|string|size:2',
            'max' => 'nullable|integer|min:1|max:500',
        ]);

        try {
            $data = $this->amadeusService->getAirlineDestinations($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchLocations(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|min:1|max:20',
            'subType' => 'required|string',
        ]);

        try {
            $data = $this->amadeusService->searchLocations(
                $validated['keyword'],
                $validated['subType'],
                $request->query('view', 'LIGHT'),
                $request->query('countryCode')
            );
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLocation(string $locationId)
    {
        try {
            $data = $this->amadeusService->getLocation($locationId);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function autocompleteLocations(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:20',  // short param name for speed
            'countryCode' => 'nullable|string|size:2',
        ]);

        try {
            $suggestions = $this->amadeusService->autocompleteLocations(
                $validated['q'],
                $validated['countryCode'] ?? null
            );
            return response()->json($suggestions);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to retrieve suggestions'], 500);
        }
    }

    public function getNearestAirports(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        try {
            $data = $this->amadeusService->getNearestAirports($validated);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDirectDestinations(Request $request)
    {
        $validated = $request->validate([
            'departureAirportCode' => 'required|string|size:3',
        ]);

        try {
            $data = $this->amadeusService->getDirectDestinations($validated['departureAirportCode']);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
