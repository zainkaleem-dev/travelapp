<?php

namespace App\Services;

use GuzzleHttp\Client;

class AmadeusService
{
    protected Client $client;
    protected string $baseUrl;
    protected ?string $apiKey;
    protected ?string $apiSecret;

    public function __construct()
    {
        $this->client = new Client([
            "verify" => false,
            "proxy" => [
                "http" => null,
                "https" => null,
                "no" => [],
            ],
        ]);
        $this->baseUrl = config("amadeus.base_url");
        $this->apiKey = config("amadeus.client_id");
        $this->apiSecret = config("amadeus.client_secret");
    }

    public function getToken(): ?string
    {
        try {
            $response = $this->client->post(
                $this->baseUrl . "/v1/security/oauth2/token",
                [
                    "form_params" => [
                        "grant_type" => "client_credentials",
                        "client_id" => $this->apiKey,
                        "client_secret" => $this->apiSecret,
                    ],
                ],
            );

            $data = json_decode($response->getBody(), true);
            $token = $data["access_token"] ?? null;

            if ($token) {
                // Small delay after refreshing a token.
                // A token refresh is an API call itself. If we immediately
                // make another call (like search) we will hit the 1 QPS limit.
                usleep(1100000); // 1.1s
            }

            return $token;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function searchLocations(
        string $keyword,
        string $subType = "CITY,AIRPORT",
        string $view = "LIGHT",
        ?string $countryCode = null,
    ) {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $query = [
            "subType" => $subType,
            "keyword" => $keyword,
            "view" => $view,
        ];

        if (!empty($countryCode)) {
            $query["countryCode"] = strtoupper($countryCode);
        }

        $url = $this->baseUrl . "/v1/reference-data/locations";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $query,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getLocation(string $locationId)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url =
            $this->baseUrl .
            "/v1/reference-data/locations/" .
            strtoupper($locationId);

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function autocompleteLocations(
        string $keyword,
        ?string $countryCode = null,
    ) {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $query = [
            "subType" => "CITY,AIRPORT",
            "keyword" => $keyword,
            "page[limit]" => 7,
            "sort" => "analytics.travelers.score",
            "view" => "LIGHT",
        ];

        if (!empty($countryCode)) {
            $query["countryCode"] = strtoupper($countryCode);
        }

        $response = $this->client->get(
            $this->baseUrl . "/v1/reference-data/locations",
            [
                "headers" => ["Authorization" => "Bearer " . $token],
                "query" => $query,
            ],
        );

        $data = json_decode($response->getBody(), true);

        return array_map(
            fn($loc) => [
                "id" => $loc["id"],
                "label" => $loc["name"] . " (" . $loc["iataCode"] . ")",
                "iata" => $loc["iataCode"],
                "subType" => $loc["subType"],
            ],
            $data["data"] ?? [],
        );
    }

    public function searchFlights(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v2/shopping/flight-offers";

        $query = [
            "originLocationCode" => strtoupper($params["originLocationCode"]),
            "destinationLocationCode" => strtoupper(
                $params["destinationLocationCode"],
            ),
            "departureDate" => $params["departureDate"],
            "adults" => $params["adults"],
        ];

        // Optional fields
        if (!empty($params["returnDate"])) {
            $query["returnDate"] = $params["returnDate"];
        }

        if (!empty($params["children"])) {
            $query["children"] = $params["children"];
        }

        if (!empty($params["infants"])) {
            $query["infants"] = $params["infants"];
        }

        if (!empty($params["travelClass"])) {
            $query["travelClass"] = strtoupper($params["travelClass"]);
        }

        if (isset($params["nonStop"])) {
            $query["nonStop"] = $params["nonStop"] ? "true" : "false";
        }

        if (!empty($params["currencyCode"])) {
            $query["currencyCode"] = strtoupper($params["currencyCode"]);
        }

        if (!empty($params["max"])) {
            $query["max"] = $params["max"];
        }

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $query,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function searchFlightDates(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        // Flight Inspiration Search API
        // Or we use Flight Cheapest Date Search (V1)
        // https://test.api.amadeus.com/v1/shopping/flight-dates
        // We will query up to 6 months around the date or strictly for that period
        $url = $this->baseUrl . "/v1/shopping/flight-dates";

        $query = [
            "origin" => strtoupper($params["origin"]),
            "destination" => strtoupper($params["destination"]),
            "departureDate" => $params["departureDate"] ?? null,
            // 'oneWay' => 'true' or 'false'
        ];

        // Ensure we filter out nulls
        $query = array_filter($query);

        try {
            $response = $this->client->get($url, [
                "headers" => [
                    "Authorization" => "Bearer " . $token,
                    "Accept" => "application/json",
                ],
                "query" => $query,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ["data" => []]; // Silently return empty on failure/404 out of range
        }
    }

    public function upsellFlightOffers(array $flightOffer)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/flight-offers/upselling";

        $body = [
            "data" => [
                "type" => "flight-offers-upselling",
                "flightOffers" => [$flightOffer],
            ],
        ];

        try {
            $response = $this->client->post($url, [
                "headers" => [
                    "Authorization" => "Bearer " . $token,
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
                "json" => $body,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ["data" => []];
        }
    }
    public function priceFlightOffer(array $flightOffers)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/flight-offers/pricing";

        $body = [
            "data" => [
                "type" => "flight-offers-pricing",
                "flightOffers" => $flightOffers,
            ],
        ];

        try {
            $response = $this->client->post($url, [
                "headers" => [
                    "Authorization" => "Bearer " . $token,
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
                "json" => $body,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning(
                "PRICE_FLIGHT_OFFER_HTTP_ERROR",
                [
                    "error" => $e->getMessage(),
                    "offers_count" => count($flightOffers),
                ],
            );
            return ["data" => null, "error" => $e->getMessage()];
        }
    }

    public function getFlightSeatmap(array $flightOffer)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/seatmaps";

        $body = [
            "data" => [$flightOffer],
        ];

        \Illuminate\Support\Facades\Log::info("Outgoing SeatMap Request", [
            "offer_id" => $flightOffer["id"] ?? "N/A",
            "itineraries_count" => count($flightOffer["itineraries"] ?? []),
            "first_segment" =>
                $flightOffer["itineraries"][0]["segments"][0]["carrierCode"] ??
                "N/A",
        ]);

        try {
            $response = $this->client->post($url, [
                "headers" => [
                    "Authorization" => "Bearer " . $token,
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
                "json" => $body,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                "SeatMap API failed: " . $e->getMessage(),
            );
            return ["data" => []];
        }
    }

    public function predictFlightChoice(array $payload)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v2/shopping/flight-offers/prediction";

        $response = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            "json" => $payload,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getFlightAvailabilities(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url =
            $this->baseUrl . "/v1/shopping/availability/flight-availabilities";

        $response = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            "json" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getAirlines(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/reference-data/airlines";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getAirlineDestinations(string $airlineCode)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url =
            $this->baseUrl .
            "/v1/reference-data/airlines/" .
            strtoupper($airlineCode) .
            "/destinations";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getNearestAirports(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/reference-data/locations/airports";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getRecommendedLocations(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/reference-data/recommended-locations";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function searchFlightsAdvanced(array $payload)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v2/shopping/flight-offers";

        // currencyCode should remain at the root of the payload
        if (isset($payload["currencyCode"])) {
            $payload["currencyCode"] = strtoupper($payload["currencyCode"]);
        }

        $response = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            "json" => $payload,
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Formats the Livewire frontend passenger session data into the strict Amadeus `travelers` schema.
     */
    public function formatPassengersForBooking(
        array $sessionPassengers,
        array $sessionContact,
    ): array {
        $travelers = [];
        $countryHelper = new \App\Helpers\CountryHelper();

        foreach ($sessionPassengers as $index => $p) {
            $dob = $p["dob"] ?? "1990-01-01";

            $gender =
                strtolower($p["gender"]) === "male"
                    ? "MALE"
                    : (strtolower($p["gender"]) === "female"
                        ? "FEMALE"
                        : "UNSPECIFIED");

            $type = $p['type'] ?? 'ADULT';
            
            $traveler = [
                "id" => (string) ($index + 1),
                "travelerType" => $type,
                "dateOfBirth" => $dob,
                "name" => [
                    "firstName" => strtoupper($p["first_name"]),
                    "lastName" => strtoupper($p["last_name"]),
                ],
                "gender" => $gender,
                "contact" => [
                    "emailAddress" => $sessionContact["email"] ?? "",
                    "phones" => [
                        [
                            "deviceType" => "MOBILE",
                            "countryCallingCode" => str_replace(
                                "+",
                                "",
                                $sessionContact["phoneCode"] ?? "1",
                            ),
                            "number" => $sessionContact["phoneNumber"] ?? "",
                        ],
                    ],
                ],
            ];

            // For INFANTS, Amadeus requires associatedAdultId
            if ($type === 'HELD_INFANT') {
                // Link to the first adult (ID "1")
                $traveler['associatedAdultId'] = "1";
            }

            // Nationality to ISO 3166-1 alpha-2 mapping
            $countryCode = \App\Helpers\CountryHelper::getCodeByName($p["nationality"] ?? "United States");

            // If Passport is provided
            if (!empty($p["passport"])) {
                $traveler["documents"] = [
                    [
                        "documentType" => "PASSPORT",
                        "number" => strtoupper($p["passport"]),
                        "issuanceCountry" => $countryCode,
                        "nationality" => $countryCode,
                        "expiryDate" => date("Y-m-d", strtotime("+5 years")), // Amadeus often requires expiry dates
                        "holder" => true,
                    ],
                ];
            }

            $travelers[] = $traveler;
        }

        return $travelers;
    }

    public function bookFlight(array $payload)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/booking/flight-orders";

        $response = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            "json" => $payload,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getFlightOrder(string $orderId)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/booking/flight-orders/" . $orderId;

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function deleteFlightOrder(string $orderId)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/booking/flight-orders/" . $orderId;

        $response = $this->client->delete($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getSeatMapByOrderId(string $orderId)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/seatmaps";
        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => ["flight-orderId" => $orderId],
        ]);
        return json_decode($response->getBody(), true);
    }

    public function getSeatMapByFlightOffer(array $flightOffer)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/seatmaps";

        $response = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            "json" => ["data" => [$flightOffer]],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getItineraryPriceMetrics(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/analytics/itinerary-price-metrics";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getFlightDestinations(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/shopping/flight-destinations";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getFlightSchedule(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v2/schedule/flights";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function predictFlightDelay(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/travel/predictions/flight-delay";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function predictAirportOnTime(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v1/travel/predictions/airport-on-time";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getCheckinLinks(array $params)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url = $this->baseUrl . "/v2/reference-data/urls/checkin-links";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
            "query" => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getDirectDestinations(string $locationCode)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new \Exception("Unable to get access token");
        }

        $url =
            $this->baseUrl .
            "/v1/reference-data/locations/" .
            strtoupper($locationCode) .
            "/airports";

        $response = $this->client->get($url, [
            "headers" => [
                "Authorization" => "Bearer " . $token,
                "Accept" => "application/json",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
