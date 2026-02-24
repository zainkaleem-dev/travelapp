<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FlightApiController extends Controller
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
        $this->baseUrl = config('amadeus.base_url');
    }
    public function getToken()
    {
        $url = $this->baseUrl . '/v1/security/oauth2/token';
        $response = $this->client->post(
            $url,
            [
                'form_params' => [
                    'grant_type'    => config('amadeus.grant_type'),
                    'client_id'     => config('amadeus.client_id'),
                    'client_secret' => config('amadeus.client_secret'),
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]
        );

        $data = json_decode($response->getBody(), true);
        return $data['access_token'] ?? null;
       
    }


    public function searchFlights(Request $request)
    {
        // 1️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 2️⃣ Call Amadeus Flight API
        $url = $this->baseUrl . '/v2/shopping/flight-offers';

        $origin = strtoupper((string) $request->query('originLocationCode', 'LON'));
        $destination = strtoupper((string) $request->query('destinationLocationCode', 'NYC'));
        $departureDate = (string) $request->query('departureDate', now()->addWeek()->format('Y-m-d'));
        $adults = max(1, (int) $request->query('adults', 1));

        $query = [
            'originLocationCode' => $origin,
            'destinationLocationCode' => $destination,
            'departureDate' => $departureDate,
            'adults' => $adults,
        ];

        if ($request->filled('children')) {
            $query['children'] = (int) $request->query('children');
        }

        if ($request->filled('infants')) {
            $query['infants'] = (int) $request->query('infants');
        }

        if ($request->filled('travelClass')) {
            $query['travelClass'] = strtoupper((string) $request->query('travelClass'));
        }

        if ($request->filled('nonStop')) {
            $query['nonStop'] = filter_var($request->query('nonStop'), FILTER_VALIDATE_BOOL) ? 'true' : 'false';
        }

        if ($request->filled('currencyCode')) {
            $query['currencyCode'] = strtoupper((string) $request->query('currencyCode'));
        }

        if ($request->filled('max')) {
            $query['max'] = max(1, (int) $request->query('max'));
        }

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'query' => $query,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch flights',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function priceFlightOffers(Request $request)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/shopping/flight-offers/pricing';

        $body = $request->all();

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode($body),
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }

    public function bookFlight()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/booking/flight-orders';

        // Example body (replace with real flight offer data & traveler info)
        $body = [
            "data" => [
                "type" => "flight-order",
                "flightOffers" => [
                    [
                        // Include the flight offer object returned from /flight-offers
                        "type" => "flight-offer",
                        "id" => "1",
                        "source" => "GDS",
                        "itineraries" => [
                            [
                                "segments" => [
                                    [
                                        "departure" => [
                                            "iataCode" => "LHR",
                                            "at" => "2026-04-01T07:00:00"
                                        ],
                                        "arrival" => [
                                            "iataCode" => "JFK",
                                            "at" => "2026-04-01T10:00:00"
                                        ],
                                        "carrierCode" => "BA",
                                        "number" => "178",
                                        "aircraft" => [
                                            "code" => "388"
                                        ],
                                        "operating" => [
                                            "carrierCode" => "BA"
                                        ],
                                        "duration" => "PT8H",
                                        "id" => "1",
                                        "numberOfStops" => 0
                                    ]
                                ]
                            ]
                        ],
                        "travelerPricings" => [
                            [
                                "travelerId" => "1",
                                "fareOption" => "STANDARD",
                                "travelerType" => "ADULT"
                            ]
                        ]
                    ]
                ],
                "travelers" => [
                    [
                        "id" => "1",
                        "dateOfBirth" => "1990-01-01",
                        "name" => [
                            "firstName" => "John",
                            "lastName" => "Doe"
                        ],
                        "gender" => "MALE",
                        "contact" => [
                            "emailAddress" => "john@example.com",
                            "phones" => [
                                [
                                    "deviceType" => "MOBILE",
                                    "countryCallingCode" => "44",
                                    "number" => "1234567890"
                                ]
                            ]
                        ],
                        "documents" => [
                            [
                                "documentType" => "PASSPORT",
                                "birthPlace" => "London",
                                "issuanceLocation" => "London",
                                "issuanceDate" => "2015-01-01",
                                "number" => "123456789",
                                "expiryDate" => "2025-01-01",
                                "nationality" => "GB",
                                "holder" => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode($body),
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }


    public function getFlightSchedule($carrierCode, $flightNumber, $departureDate)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v2/schedule/flights';

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'query' => [
                'carrierCode' => $carrierCode,
                'flightNumber' => $flightNumber,
                'scheduledDepartureDate' => $departureDate,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }


    public function predictFlightDelay(Request $request)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/travel/predictions/flight-delay';

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'query' => $request->query(),
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }

    public function predictAirportOnTime($airportCode, $date)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/airport/predictions/on-time';

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'query' => [
                'airportCode' => $airportCode,
                'date'        => $date,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }

    public function flightPriceAnalysis(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/analytics/itinerary-price-metrics';

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'query' => $params,
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json($data);
    }

    
}
