<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FlightApiController extends Controller
{
    protected $client;
    protected $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
            'proxy' => [
                'http' => null,
                'https' => null,
                'no' => [],
            ],
        ]);
        $this->baseUrl = config('amadeus.base_url');
        $this->apiKey = config('amadeus.api_key');
        $this->apiSecret = config('amadeus.api_secret');
    }
    private function getToken(): ?string
    {
        return Cache::remember('amadeus_token', 1700, function () {
            try {
                $response = $this->client->post($this->baseUrl . '/v1/security/oauth2/token', [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->apiKey,
                        'client_secret' => $this->apiSecret,
                    ],
                ]);

                $data = json_decode($response->getBody(), true);

                return $data['access_token'] ?? null;

            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    public function searchFlights(Request $request)
    {
        // 1️⃣ Validate required inputs
        $validated = $request->validate([
            'originLocationCode' => 'required|string|size:3',
            'destinationLocationCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m-d|after_or_equal:today',
            'returnDate' => 'nullable|date_format:Y-m-d|after_or_equal:departureDate',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'infants' => 'nullable|integer|min:0|max:9',
            'travelClass' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'nonStop' => 'nullable|boolean',
            'currencyCode' => 'nullable|string|size:3',
            'max' => 'nullable|integer|min:1|max:250',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query
        $url = $this->baseUrl . '/v2/shopping/flight-offers';

        $query = [
            'originLocationCode' => strtoupper($validated['originLocationCode']),
            'destinationLocationCode' => strtoupper($validated['destinationLocationCode']),
            'departureDate' => $validated['departureDate'],
            'adults' => $validated['adults'],
        ];

        // Optional fields
        if (!empty($validated['returnDate'])) {
            $query['returnDate'] = $validated['returnDate'];
        }

        if (!empty($validated['children'])) {
            $query['children'] = $validated['children'];
        }

        if (!empty($validated['infants'])) {
            $query['infants'] = $validated['infants'];
        }

        if (!empty($validated['travelClass'])) {
            $query['travelClass'] = strtoupper($validated['travelClass']);
        }

        if (isset($validated['nonStop'])) {
            $query['nonStop'] = $validated['nonStop'] ? 'true' : 'false';
        }

        if (!empty($validated['currencyCode'])) {
            $query['currencyCode'] = strtoupper($validated['currencyCode']);
        }

        if (!empty($validated['max'])) {
            $query['max'] = $validated['max'];
        }

        // 4️⃣ Call Amadeus API
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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // 4xx errors from Amadeus (e.g. bad params)
            $body = json_decode($e->getResponse()->getBody(), true);
            return response()->json([
                'error' => 'Amadeus API error',
                'details' => $body,
            ], $e->getResponse()->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // 5xx errors from Amadeus
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch flights',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchFlightsPost(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'currencyCode' => 'nullable|string|size:3',
            'originDestinations' => 'required|array|min:1',
            'originDestinations.*.id' => 'required|string',
            'originDestinations.*.originLocationCode' => 'required|string|size:3',
            'originDestinations.*.destinationLocationCode' => 'required|string|size:3',
            'originDestinations.*.departureDateTimeRange.date' => 'required|date_format:Y-m-d',
            'originDestinations.*.departureDateTimeRange.time' => 'nullable|date_format:H:i:s',

            'travelers' => 'required|array|min:1',
            'travelers.*.id' => 'required|string',
            'travelers.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'travelers.*.fareOptions' => 'nullable|array',
            'travelers.*.fareOptions.*' => 'string|in:STANDARD,INCLUSIVE_TOUR,SPANISH_MELILLA_RESIDENT,SPANISH_CEUTA_RESIDENT,SPANISH_CANARY_RESIDENT,SPANISH_BALEARIC_RESIDENT,AIR_FRANCE_METROPOLITAN_DISCOUNT_PASS,AIR_FRANCE_DOM_DISCOUNT_PASS,AIR_FRANCE_COMBINED_DISCOUNT_PASS,AIR_FRANCE_FAMILY,ADULT_WITH_COMPANION,COMPANION',

            'sources' => 'required|array|min:1',
            'sources.*' => 'string|in:GDS',

            'searchCriteria' => 'nullable|array',
            'searchCriteria.maxFlightOffers' => 'nullable|integer|min:1|max:250',
            'searchCriteria.flightFilters' => 'nullable|array',

            // Cabin restrictions
            'searchCriteria.flightFilters.cabinRestrictions' => 'nullable|array',
            'searchCriteria.flightFilters.cabinRestrictions.*.cabin' => 'required_with:searchCriteria.flightFilters.cabinRestrictions|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'searchCriteria.flightFilters.cabinRestrictions.*.coverage' => 'required_with:searchCriteria.flightFilters.cabinRestrictions|in:MOST_SEGMENTS,AT_LEAST_ONE_SEGMENT,ALL_SEGMENTS',
            'searchCriteria.flightFilters.cabinRestrictions.*.originDestinationIds' => 'required_with:searchCriteria.flightFilters.cabinRestrictions|array',
            'searchCriteria.flightFilters.cabinRestrictions.*.originDestinationIds.*' => 'string',

            // Carrier restrictions
            'searchCriteria.flightFilters.carrierRestrictions' => 'nullable|array',
            'searchCriteria.flightFilters.carrierRestrictions.excludedCarrierCodes' => 'nullable|array',
            'searchCriteria.flightFilters.carrierRestrictions.excludedCarrierCodes.*' => 'string|size:2',
            'searchCriteria.flightFilters.carrierRestrictions.includedCarrierCodes' => 'nullable|array',
            'searchCriteria.flightFilters.carrierRestrictions.includedCarrierCodes.*' => 'string|size:2',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build the POST body
        $payload = [
            'currencyCode' => strtoupper($validated['currencyCode'] ?? 'USD'),
            'originDestinations' => array_map(function ($od) {
                $mapped = [
                    'id' => $od['id'],
                    'originLocationCode' => strtoupper($od['originLocationCode']),
                    'destinationLocationCode' => strtoupper($od['destinationLocationCode']),
                    'departureDateTimeRange' => [
                        'date' => $od['departureDateTimeRange']['date'],
                    ],
                ];

                if (!empty($od['departureDateTimeRange']['time'])) {
                    $mapped['departureDateTimeRange']['time'] = $od['departureDateTimeRange']['time'];
                }

                return $mapped;
            }, $validated['originDestinations']),

            'travelers' => array_map(function ($traveler) {
                return [
                    'id' => $traveler['id'],
                    'travelerType' => $traveler['travelerType'],
                    'fareOptions' => $traveler['fareOptions'] ?? ['STANDARD'],
                ];
            }, $validated['travelers']),

            'sources' => $validated['sources'],
        ];

        // 4️⃣ Append searchCriteria only if provided
        if (!empty($validated['searchCriteria'])) {
            $searchCriteria = [];

            if (!empty($validated['searchCriteria']['maxFlightOffers'])) {
                $searchCriteria['maxFlightOffers'] = $validated['searchCriteria']['maxFlightOffers'];
            }

            if (!empty($validated['searchCriteria']['flightFilters'])) {
                $flightFilters = [];

                if (!empty($validated['searchCriteria']['flightFilters']['cabinRestrictions'])) {
                    $flightFilters['cabinRestrictions'] = $validated['searchCriteria']['flightFilters']['cabinRestrictions'];
                }

                if (!empty($validated['searchCriteria']['flightFilters']['carrierRestrictions'])) {
                    $carrierRestrictions = [];

                    if (!empty($validated['searchCriteria']['flightFilters']['carrierRestrictions']['excludedCarrierCodes'])) {
                        $carrierRestrictions['excludedCarrierCodes'] = array_map(
                            'strtoupper',
                            $validated['searchCriteria']['flightFilters']['carrierRestrictions']['excludedCarrierCodes']
                        );
                    }

                    if (!empty($validated['searchCriteria']['flightFilters']['carrierRestrictions']['includedCarrierCodes'])) {
                        $carrierRestrictions['includedCarrierCodes'] = array_map(
                            'strtoupper',
                            $validated['searchCriteria']['flightFilters']['carrierRestrictions']['includedCarrierCodes']
                        );
                    }

                    $flightFilters['carrierRestrictions'] = $carrierRestrictions;
                }

                $searchCriteria['flightFilters'] = $flightFilters;
            }

            $payload['searchCriteria'] = $searchCriteria;
        }

        // 5️⃣ Call Amadeus API
        $url = $this->baseUrl . '/v2/shopping/flight-offers';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = json_decode($e->getResponse()->getBody(), true);
            return response()->json([
                'error' => 'Amadeus API error',
                'details' => $body,
            ], $e->getResponse()->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch flights',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function bookFlight(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Flight Offer (taken from pricing response)
            'flightOffers' => 'required|array|min:1',
            'flightOffers.*.type' => 'required|string',
            'flightOffers.*.id' => 'required|string',
            'flightOffers.*.source' => 'required|string',
            'flightOffers.*.itineraries' => 'required|array|min:1',
            'flightOffers.*.price' => 'required|array',
            'flightOffers.*.price.currency' => 'required|string|size:3',
            'flightOffers.*.price.total' => 'required|string',
            'flightOffers.*.price.base' => 'required|string',
            'flightOffers.*.travelerPricings' => 'required|array|min:1',

            // Travelers
            'travelers' => 'required|array|min:1',
            'travelers.*.id' => 'required|string',
            'travelers.*.dateOfBirth' => 'required|date_format:Y-m-d',
            'travelers.*.name' => 'required|array',
            'travelers.*.name.firstName' => 'required|string|max:50',
            'travelers.*.name.lastName' => 'required|string|max:50',
            'travelers.*.gender' => 'required|in:MALE,FEMALE',
            'travelers.*.contact' => 'required|array',
            'travelers.*.contact.emailAddress' => 'required|email',
            'travelers.*.contact.phones' => 'required|array|min:1',
            'travelers.*.contact.phones.*.deviceType' => 'required|in:MOBILE,LANDLINE,FAX',
            'travelers.*.contact.phones.*.countryCallingCode' => 'required|string',
            'travelers.*.contact.phones.*.number' => 'required|string',
            'travelers.*.documents' => 'nullable|array',
            'travelers.*.documents.*.documentType' => 'required_with:travelers.*.documents|in:PASSPORT,IDENTITY_CARD,VISA,KNOWN_TRAVELER,REDRESS',
            'travelers.*.documents.*.number' => 'required_with:travelers.*.documents|string',
            'travelers.*.documents.*.expiryDate' => 'required_with:travelers.*.documents|date_format:Y-m-d',
            'travelers.*.documents.*.issuanceCountry' => 'required_with:travelers.*.documents|string|size:2',
            'travelers.*.documents.*.nationality' => 'required_with:travelers.*.documents|string|size:2',
            'travelers.*.documents.*.holder' => 'required_with:travelers.*.documents|boolean',

            // Remarks (optional)
            'remarks' => 'nullable|array',
            'remarks.general' => 'nullable|array',
            'remarks.general.*.subType' => 'required_with:remarks.general|in:GENERAL_MISCELLANEOUS,CONFIDENTIAL,INVOICE,QUALITY_CONTROL,BACKOFFICE,FULFILLMENT,ITINERARY,TICKETING_MISCELLANEOUS,TOUR_CODE',
            'remarks.general.*.text' => 'required_with:remarks.general|string|max:250',

            // Ticketing Agreement (optional)
            'ticketingAgreement' => 'nullable|array',
            'ticketingAgreement.option' => 'nullable|in:ON_HOLD,DELAY_TO_CANCEL,CONFIRM',
            'ticketingAgreement.delay' => 'nullable|string',

            // Contacts (optional - travel agency contact)
            'contacts' => 'nullable|array',
            'contacts.*.addresseeName' => 'nullable|array',
            'contacts.*.addresseeName.firstName' => 'nullable|string',
            'contacts.*.addresseeName.lastName' => 'nullable|string',
            'contacts.*.companyName' => 'nullable|string',
            'contacts.*.purpose' => 'nullable|in:STANDARD,INVOICE,STANDARD_WITHOUT_TRANSMISSION',
            'contacts.*.phones' => 'nullable|array',
            'contacts.*.phones.*.deviceType' => 'nullable|in:MOBILE,LANDLINE,FAX',
            'contacts.*.phones.*.countryCallingCode' => 'nullable|string',
            'contacts.*.phones.*.number' => 'nullable|string',
            'contacts.*.emailAddress' => 'nullable|email',
            'contacts.*.address' => 'nullable|array',
            'contacts.*.address.lines' => 'nullable|array',
            'contacts.*.address.postalCode' => 'nullable|string',
            'contacts.*.address.cityName' => 'nullable|string',
            'contacts.*.address.countryCode' => 'nullable|string|size:2',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build the POST payload
        $payload = [
            'data' => [
                'type' => 'flight-order',
                'flightOffers' => $validated['flightOffers'],
                'travelers' => array_map(function ($traveler) {
                    $mapped = [
                        'id' => $traveler['id'],
                        'dateOfBirth' => $traveler['dateOfBirth'],
                        'name' => [
                            'firstName' => strtoupper($traveler['name']['firstName']),
                            'lastName' => strtoupper($traveler['name']['lastName']),
                        ],
                        'gender' => $traveler['gender'],
                        'contact' => [
                            'emailAddress' => $traveler['contact']['emailAddress'],
                            'phones' => $traveler['contact']['phones'],
                        ],
                    ];

                    // Append travel documents if provided
                    if (!empty($traveler['documents'])) {
                        $mapped['documents'] = $traveler['documents'];
                    }

                    return $mapped;
                }, $validated['travelers']),
            ],
        ];

        // 4️⃣ Append optional fields if provided
        if (!empty($validated['remarks'])) {
            $payload['data']['remarks'] = $validated['remarks'];
        }

        if (!empty($validated['ticketingAgreement'])) {
            $payload['data']['ticketingAgreement'] = $validated['ticketingAgreement'];
        }

        if (!empty($validated['contacts'])) {
            $payload['data']['contacts'] = $validated['contacts'];
        }

        // 5️⃣ Call Amadeus Booking API
        $url = $this->baseUrl . '/v1/booking/flight-orders';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data, 201);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = json_decode($e->getResponse()->getBody(), true);
            return response()->json([
                'error' => 'Amadeus booking error',
                'details' => $body,
            ], $e->getResponse()->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to create flight order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function priceFlightOffer(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Flight Offers (taken directly from search response)
            'flightOffers' => 'required|array|min:1',
            'flightOffers.*.type' => 'required|string',
            'flightOffers.*.id' => 'required|string',
            'flightOffers.*.source' => 'required|string',
            'flightOffers.*.instantTicketingRequired' => 'nullable|boolean',
            'flightOffers.*.nonHomogeneous' => 'nullable|boolean',
            'flightOffers.*.oneWay' => 'nullable|boolean',
            'flightOffers.*.lastTicketingDate' => 'nullable|date_format:Y-m-d',
            'flightOffers.*.numberOfBookableSeats' => 'nullable|integer',

            // Itineraries
            'flightOffers.*.itineraries' => 'required|array|min:1',
            'flightOffers.*.itineraries.*.duration' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments' => 'required|array|min:1',
            'flightOffers.*.itineraries.*.segments.*.departure' => 'required|array',
            'flightOffers.*.itineraries.*.segments.*.departure.iataCode' => 'required|string|size:3',
            'flightOffers.*.itineraries.*.segments.*.departure.terminal' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.departure.at' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.arrival' => 'required|array',
            'flightOffers.*.itineraries.*.segments.*.arrival.iataCode' => 'required|string|size:3',
            'flightOffers.*.itineraries.*.segments.*.arrival.terminal' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.arrival.at' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.carrierCode' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.number' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.aircraft' => 'nullable|array',
            'flightOffers.*.itineraries.*.segments.*.aircraft.code' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.operating' => 'nullable|array',
            'flightOffers.*.itineraries.*.segments.*.operating.carrierCode' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.duration' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.id' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.numberOfStops' => 'nullable|integer',
            'flightOffers.*.itineraries.*.segments.*.blacklistedInEU' => 'nullable|boolean',

            // Price
            'flightOffers.*.price' => 'required|array',
            'flightOffers.*.price.currency' => 'required|string|size:3',
            'flightOffers.*.price.total' => 'required|string',
            'flightOffers.*.price.base' => 'required|string',
            'flightOffers.*.price.fees' => 'nullable|array',
            'flightOffers.*.price.fees.*.amount' => 'required_with:flightOffers.*.price.fees|string',
            'flightOffers.*.price.fees.*.type' => 'required_with:flightOffers.*.price.fees|in:TICKETING,FORM_OF_PAYMENT,SUPPLIER',
            'flightOffers.*.price.grandTotal' => 'nullable|string',

            // Pricing Options
            'flightOffers.*.pricingOptions' => 'nullable|array',
            'flightOffers.*.pricingOptions.fareType' => 'nullable|array',
            'flightOffers.*.pricingOptions.fareType.*' => 'string|in:PUBLISHED,NEGOTIATED,CORPORATE',
            'flightOffers.*.pricingOptions.includedCheckedBagsOnly' => 'nullable|boolean',

            // Traveler Pricings
            'flightOffers.*.travelerPricings' => 'required|array|min:1',
            'flightOffers.*.travelerPricings.*.travelerId' => 'required|string',
            'flightOffers.*.travelerPricings.*.fareOption' => 'required|in:STANDARD,INCLUSIVE_TOUR,SPANISH_MELILLA_RESIDENT,SPANISH_CEUTA_RESIDENT,SPANISH_CANARY_RESIDENT,SPANISH_BALEARIC_RESIDENT,AIR_FRANCE_METROPOLITAN_DISCOUNT_PASS,AIR_FRANCE_DOM_DISCOUNT_PASS,AIR_FRANCE_COMBINED_DISCOUNT_PASS,AIR_FRANCE_FAMILY,ADULT_WITH_COMPANION,COMPANION',
            'flightOffers.*.travelerPricings.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'flightOffers.*.travelerPricings.*.price' => 'nullable|array',
            'flightOffers.*.travelerPricings.*.price.currency' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.price.total' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.price.base' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment' => 'required|array|min:1',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.segmentId' => 'required|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.cabin' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.fareBasis' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.brandedFare' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.class' => 'nullable|string|size:1',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags' => 'nullable|array',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.quantity' => 'nullable|integer',

            // Optional query params
            'include' => 'nullable|array',
            'include.*' => 'string|in:credit-card-fees,bags,other-services,detailed-fare-rules',
            'forceClass' => 'nullable|boolean',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build POST payload
        $payload = [
            'data' => [
                'type' => 'flight-offers-pricing',
                'flightOffers' => $validated['flightOffers'],
            ],
        ];

        // 4️⃣ Build optional query params
        $queryParams = [];

        if (!empty($validated['include'])) {
            // Amadeus expects: ?include=bags,other-services
            $queryParams['include'] = implode(',', $validated['include']);
        }

        if (isset($validated['forceClass'])) {
            $queryParams['forceClass'] = $validated['forceClass'] ? 'true' : 'false';
        }

        // 5️⃣ Call Amadeus Pricing API
        $url = $this->baseUrl . '/v1/shopping/flight-offers/pricing';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
                'query' => $queryParams,
            ]);

            $data = json_decode($response->getBody(), true);

            // 6️⃣ Return confirmed pricing data
            // This response should be passed directly to the booking endpoint
            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = json_decode($e->getResponse()->getBody(), true);
            return response()->json([
                'error' => 'Amadeus pricing error',
                'details' => $body,
            ], $e->getResponse()->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to price flight offer',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFlightOrder(string $orderId)
    {
        // 1️⃣ Validate order ID
        if (empty($orderId)) {
            return response()->json(['error' => 'Order ID is required'], 400);
        }

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ URL encode the order ID (Amadeus IDs contain special characters e.g. eJzTd9cPDAgICfYAAAvRAoY=)
        $encodedOrderId = urlencode($orderId);

        // 4️⃣ Call Amadeus Get Flight Order API
        $url = $this->baseUrl . '/v1/booking/flight-orders/' . $encodedOrderId;

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = json_decode($e->getResponse()->getBody(), true);

            // Handle 404 specifically - order not found
            if ($e->getResponse()->getStatusCode() === 404) {
                return response()->json([
                    'error' => 'Flight order not found',
                    'orderId' => $orderId,
                ], 404);
            }

            return response()->json([
                'error' => 'Amadeus API error',
                'details' => $body,
            ], $e->getResponse()->getStatusCode());

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteFlightOrder(string $orderId)
    {
        // 1️⃣ Validate order ID
        if (empty($orderId)) {
            return response()->json(['error' => 'Order ID is required'], 400);
        }

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ URL encode the order ID (Amadeus IDs contain special characters e.g. eJzTd9cPDAgICfYAAAvRAoY=)
        $encodedOrderId = urlencode($orderId);

        // 4️⃣ Call Amadeus Delete Flight Order API
        $url = $this->baseUrl . '/v1/booking/flight-orders/' . $encodedOrderId;

        try {
            $response = $this->client->delete($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            // Amadeus returns 200 with empty body on successful delete
            if ($response->getStatusCode() === 200) {
                return response()->json([
                    'message' => 'Flight order successfully cancelled',
                    'orderId' => $orderId,
                ], 200);
            }

            return response()->json([
                'message' => 'Flight order deletion processed',
                'orderId' => $orderId,
            ], $response->getStatusCode());

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            // Handle specific error cases
            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid order ID format',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'Flight order not found',
                    'orderId' => $orderId,
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to delete flight order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSeatMapByOrderId(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'flight-orderId' => 'required|string',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ URL encode the order ID (Amadeus IDs contain special chars e.g. eJzTd9cPDAgICfYAAAvRAoY=)
        $encodedOrderId = urlencode($validated['flight-orderId']);

        // 4️⃣ Call Amadeus Seat Map API
        $url = $this->baseUrl . '/v1/shopping/seatmaps';

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'flight-orderId' => $encodedOrderId,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid flight order ID format',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'Seat map not found for this flight order',
                    'flightOrderId' => $validated['flight-orderId'],
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve seat map',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSeatMapByFlightOffer(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Flight Offers Array
            'data' => 'required|array|min:1',
            'data.*.type' => 'required|string',
            'data.*.id' => 'required|string',
            'data.*.source' => 'required|string',
            'data.*.instantTicketingRequired' => 'nullable|boolean',
            'data.*.nonHomogeneous' => 'nullable|boolean',
            'data.*.oneWay' => 'nullable|boolean',
            'data.*.lastTicketingDate' => 'nullable|date_format:Y-m-d',
            'data.*.numberOfBookableSeats' => 'nullable|integer',

            // Itineraries
            'data.*.itineraries' => 'required|array|min:1',
            'data.*.itineraries.*.duration' => 'nullable|string',
            'data.*.itineraries.*.segments' => 'required|array|min:1',

            // Segments
            'data.*.itineraries.*.segments.*.id' => 'required|string',
            'data.*.itineraries.*.segments.*.numberOfStops' => 'nullable|integer',
            'data.*.itineraries.*.segments.*.blacklistedInEU' => 'nullable|boolean',
            'data.*.itineraries.*.segments.*.duration' => 'nullable|string',
            'data.*.itineraries.*.segments.*.carrierCode' => 'required|string',
            'data.*.itineraries.*.segments.*.number' => 'required|string',

            // Departure
            'data.*.itineraries.*.segments.*.departure' => 'required|array',
            'data.*.itineraries.*.segments.*.departure.iataCode' => 'required|string|size:3',
            'data.*.itineraries.*.segments.*.departure.terminal' => 'nullable|string',
            'data.*.itineraries.*.segments.*.departure.at' => 'required|string',

            // Arrival
            'data.*.itineraries.*.segments.*.arrival' => 'required|array',
            'data.*.itineraries.*.segments.*.arrival.iataCode' => 'required|string|size:3',
            'data.*.itineraries.*.segments.*.arrival.terminal' => 'nullable|string',
            'data.*.itineraries.*.segments.*.arrival.at' => 'required|string',

            // Aircraft
            'data.*.itineraries.*.segments.*.aircraft' => 'nullable|array',
            'data.*.itineraries.*.segments.*.aircraft.code' => 'nullable|string',

            // Operating
            'data.*.itineraries.*.segments.*.operating' => 'nullable|array',
            'data.*.itineraries.*.segments.*.operating.carrierCode' => 'nullable|string',

            // Price
            'data.*.price' => 'required|array',
            'data.*.price.currency' => 'required|string|size:3',
            'data.*.price.total' => 'required|string',
            'data.*.price.base' => 'required|string',
            'data.*.price.grandTotal' => 'nullable|string',
            'data.*.price.fees' => 'nullable|array',
            'data.*.price.fees.*.amount' => 'required_with:data.*.price.fees|string',
            'data.*.price.fees.*.type' => 'required_with:data.*.price.fees|in:TICKETING,FORM_OF_PAYMENT,SUPPLIER',

            // Pricing Options
            'data.*.pricingOptions' => 'nullable|array',
            'data.*.pricingOptions.fareType' => 'nullable|array',
            'data.*.pricingOptions.fareType.*' => 'string|in:PUBLISHED,NEGOTIATED,CORPORATE',
            'data.*.pricingOptions.includedCheckedBagsOnly' => 'nullable|boolean',

            // Traveler Pricings
            'data.*.travelerPricings' => 'required|array|min:1',
            'data.*.travelerPricings.*.travelerId' => 'required|string',
            'data.*.travelerPricings.*.fareOption' => 'required|in:STANDARD,INCLUSIVE_TOUR,SPANISH_MELILLA_RESIDENT,SPANISH_CEUTA_RESIDENT,SPANISH_CANARY_RESIDENT,SPANISH_BALEARIC_RESIDENT,AIR_FRANCE_METROPOLITAN_DISCOUNT_PASS,AIR_FRANCE_DOM_DISCOUNT_PASS,AIR_FRANCE_COMBINED_DISCOUNT_PASS,AIR_FRANCE_FAMILY,ADULT_WITH_COMPANION,COMPANION',
            'data.*.travelerPricings.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'data.*.travelerPricings.*.price' => 'nullable|array',
            'data.*.travelerPricings.*.price.currency' => 'nullable|string',
            'data.*.travelerPricings.*.price.total' => 'nullable|string',
            'data.*.travelerPricings.*.price.base' => 'nullable|string',

            // Fare Details by Segment
            'data.*.travelerPricings.*.fareDetailsBySegment' => 'required|array|min:1',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.segmentId' => 'required|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.cabin' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.fareBasis' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.brandedFare' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.class' => 'nullable|string|size:1',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags' => 'nullable|array',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.quantity' => 'nullable|integer',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build POST payload
        // Amadeus expects { "data": [ ...flightOffers ] }
        $payload = [
            'data' => $validated['data'],
        ];

        // 4️⃣ Call Amadeus Seat Map POST API
        $url = $this->baseUrl . '/v1/shopping/seatmaps';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid flight offer data',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'Seat map not found for the provided flight offer',
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve seat map',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function upsellFlightOffer(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Flight Offers
            'flightOffers' => 'required|array|min:1',
            'flightOffers.*.type' => 'required|string',
            'flightOffers.*.id' => 'required|string',
            'flightOffers.*.source' => 'required|string',
            'flightOffers.*.instantTicketingRequired' => 'nullable|boolean',
            'flightOffers.*.nonHomogeneous' => 'nullable|boolean',
            'flightOffers.*.oneWay' => 'nullable|boolean',
            'flightOffers.*.lastTicketingDate' => 'nullable|date_format:Y-m-d',
            'flightOffers.*.numberOfBookableSeats' => 'nullable|integer',

            // Itineraries
            'flightOffers.*.itineraries' => 'required|array|min:1',
            'flightOffers.*.itineraries.*.duration' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments' => 'required|array|min:1',

            // Segments
            'flightOffers.*.itineraries.*.segments.*.id' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.numberOfStops' => 'nullable|integer',
            'flightOffers.*.itineraries.*.segments.*.blacklistedInEU' => 'nullable|boolean',
            'flightOffers.*.itineraries.*.segments.*.duration' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.carrierCode' => 'required|string',
            'flightOffers.*.itineraries.*.segments.*.number' => 'required|string',

            // Departure
            'flightOffers.*.itineraries.*.segments.*.departure' => 'required|array',
            'flightOffers.*.itineraries.*.segments.*.departure.iataCode' => 'required|string|size:3',
            'flightOffers.*.itineraries.*.segments.*.departure.terminal' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.departure.at' => 'required|string',

            // Arrival
            'flightOffers.*.itineraries.*.segments.*.arrival' => 'required|array',
            'flightOffers.*.itineraries.*.segments.*.arrival.iataCode' => 'required|string|size:3',
            'flightOffers.*.itineraries.*.segments.*.arrival.terminal' => 'nullable|string',
            'flightOffers.*.itineraries.*.segments.*.arrival.at' => 'required|string',

            // Aircraft
            'flightOffers.*.itineraries.*.segments.*.aircraft' => 'nullable|array',
            'flightOffers.*.itineraries.*.segments.*.aircraft.code' => 'nullable|string',

            // Operating
            'flightOffers.*.itineraries.*.segments.*.operating' => 'nullable|array',
            'flightOffers.*.itineraries.*.segments.*.operating.carrierCode' => 'nullable|string',

            // Price
            'flightOffers.*.price' => 'required|array',
            'flightOffers.*.price.currency' => 'required|string|size:3',
            'flightOffers.*.price.total' => 'required|string',
            'flightOffers.*.price.base' => 'required|string',
            'flightOffers.*.price.grandTotal' => 'nullable|string',
            'flightOffers.*.price.fees' => 'nullable|array',
            'flightOffers.*.price.fees.*.amount' => 'required_with:flightOffers.*.price.fees|string',
            'flightOffers.*.price.fees.*.type' => 'required_with:flightOffers.*.price.fees|in:TICKETING,FORM_OF_PAYMENT,SUPPLIER',

            // Pricing Options
            'flightOffers.*.pricingOptions' => 'nullable|array',
            'flightOffers.*.pricingOptions.fareType' => 'nullable|array',
            'flightOffers.*.pricingOptions.fareType.*' => 'string|in:PUBLISHED,NEGOTIATED,CORPORATE',
            'flightOffers.*.pricingOptions.includedCheckedBagsOnly' => 'nullable|boolean',

            // Traveler Pricings
            'flightOffers.*.travelerPricings' => 'required|array|min:1',
            'flightOffers.*.travelerPricings.*.travelerId' => 'required|string',
            'flightOffers.*.travelerPricings.*.fareOption' => 'required|in:STANDARD,INCLUSIVE_TOUR,SPANISH_MELILLA_RESIDENT,SPANISH_CEUTA_RESIDENT,SPANISH_CANARY_RESIDENT,SPANISH_BALEARIC_RESIDENT,AIR_FRANCE_METROPOLITAN_DISCOUNT_PASS,AIR_FRANCE_DOM_DISCOUNT_PASS,AIR_FRANCE_COMBINED_DISCOUNT_PASS,AIR_FRANCE_FAMILY,ADULT_WITH_COMPANION,COMPANION',
            'flightOffers.*.travelerPricings.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'flightOffers.*.travelerPricings.*.price' => 'nullable|array',
            'flightOffers.*.travelerPricings.*.price.currency' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.price.total' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.price.base' => 'nullable|string',

            // Fare Details by Segment
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment' => 'required|array|min:1',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.segmentId' => 'required|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.cabin' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.fareBasis' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.brandedFare' => 'nullable|string',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.class' => 'nullable|string|size:1',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags' => 'nullable|array',
            'flightOffers.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.quantity' => 'nullable|integer',

            // Payments (optional — for card surcharge calculation)
            'payments' => 'nullable|array',
            'payments.*.brand' => 'required_with:payments|string|in:VISA,VISA_ELECTRON,VISA_IXARIS,VISA_PURCHASING,MASTERCARD,MASTERCARD_IXARIS,AMERICAN_EXPRESS,VISA_DEBIT,MASTERCARD_DEBIT,MAESTRO,DINERS,DISCOVER,TROY,UATP,UATP_IXARIS',
            'payments.*.binNumber' => 'required_with:payments|integer|digits:6',
            'payments.*.flightOfferIds' => 'required_with:payments|array|min:1',
            'payments.*.flightOfferIds.*' => 'integer',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build POST payload
        $payload = [
            'data' => [
                'type' => 'flight-offers-upselling',
                'flightOffers' => $validated['flightOffers'],
            ],
        ];

        // 4️⃣ Append payments only if provided
        // Payments are used to calculate card-specific surcharges on upsell offers
        if (!empty($validated['payments'])) {
            $payload['data']['payments'] = $validated['payments'];
        }

        // 5️⃣ Call Amadeus Upselling API
        $url = $this->baseUrl . '/v1/shopping/flight-offers/upselling';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid flight offer data',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No upsell offers found for the provided flight offer',
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve upsell offers',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getItineraryPriceMetrics(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'originIataCode' => 'required|string|size:3',
            'destinationIataCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m',      // Format: YYYY-MM
            'currencyCode' => 'nullable|string|size:3',
            'oneWay' => 'nullable|boolean',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'originIataCode' => strtoupper($validated['originIataCode']),
            'destinationIataCode' => strtoupper($validated['destinationIataCode']),
            'departureDate' => $validated['departureDate'],
        ];

        if (!empty($validated['currencyCode'])) {
            $query['currencyCode'] = strtoupper($validated['currencyCode']);
        }

        if (isset($validated['oneWay'])) {
            $query['oneWay'] = $validated['oneWay'] ? 'true' : 'false';
        }

        // 4️⃣ Call Amadeus Itinerary Price Metrics API
        $url = $this->baseUrl . '/v1/analytics/itinerary-price-metrics';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No price metrics found for this route and date',
                    'route' => strtoupper($validated['originIataCode']) . ' → ' . strtoupper($validated['destinationIataCode']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve price metrics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function predictFlightChoice(Request $request)
    {
        // 1️⃣ Validate incoming request
        // This endpoint accepts the full flight-offers search response as input
        $validated = $request->validate([
            // Meta
            'meta' => 'nullable|array',
            'meta.count' => 'nullable|integer',
            'meta.links' => 'nullable|array',
            'meta.links.self' => 'nullable|string|url',

            // Flight Offers
            'data' => 'required|array|min:1',
            'data.*.type' => 'required|string',
            'data.*.id' => 'required|string',
            'data.*.source' => 'required|string',
            'data.*.instantTicketingRequired' => 'nullable|boolean',
            'data.*.nonHomogeneous' => 'nullable|boolean',
            'data.*.oneWay' => 'nullable|boolean',
            'data.*.lastTicketingDate' => 'nullable|date_format:Y-m-d',
            'data.*.numberOfBookableSeats' => 'nullable|integer',

            // Itineraries
            'data.*.itineraries' => 'required|array|min:1',
            'data.*.itineraries.*.duration' => 'nullable|string',
            'data.*.itineraries.*.segments' => 'required|array|min:1',

            // Segments
            'data.*.itineraries.*.segments.*.id' => 'required|string',
            'data.*.itineraries.*.segments.*.numberOfStops' => 'nullable|integer',
            'data.*.itineraries.*.segments.*.blacklistedInEU' => 'nullable|boolean',
            'data.*.itineraries.*.segments.*.duration' => 'nullable|string',
            'data.*.itineraries.*.segments.*.carrierCode' => 'required|string',
            'data.*.itineraries.*.segments.*.number' => 'required|string',

            // Departure
            'data.*.itineraries.*.segments.*.departure' => 'required|array',
            'data.*.itineraries.*.segments.*.departure.iataCode' => 'required|string|size:3',
            'data.*.itineraries.*.segments.*.departure.terminal' => 'nullable|string',
            'data.*.itineraries.*.segments.*.departure.at' => 'required|string',

            // Arrival
            'data.*.itineraries.*.segments.*.arrival' => 'required|array',
            'data.*.itineraries.*.segments.*.arrival.iataCode' => 'required|string|size:3',
            'data.*.itineraries.*.segments.*.arrival.terminal' => 'nullable|string',
            'data.*.itineraries.*.segments.*.arrival.at' => 'required|string',

            // Aircraft & Operating
            'data.*.itineraries.*.segments.*.aircraft.code' => 'nullable|string',
            'data.*.itineraries.*.segments.*.operating.carrierCode' => 'nullable|string',

            // Price
            'data.*.price' => 'required|array',
            'data.*.price.currency' => 'required|string|size:3',
            'data.*.price.total' => 'required|string',
            'data.*.price.base' => 'required|string',
            'data.*.price.grandTotal' => 'nullable|string',
            'data.*.price.fees' => 'nullable|array',
            'data.*.price.fees.*.amount' => 'required_with:data.*.price.fees|string',
            'data.*.price.fees.*.type' => 'required_with:data.*.price.fees|in:TICKETING,FORM_OF_PAYMENT,SUPPLIER',
            'data.*.price.additionalServices' => 'nullable|array',
            'data.*.price.additionalServices.*.amount' => 'nullable|string',
            'data.*.price.additionalServices.*.type' => 'nullable|string',

            // Pricing Options
            'data.*.pricingOptions.fareType' => 'nullable|array',
            'data.*.pricingOptions.fareType.*' => 'string|in:PUBLISHED,NEGOTIATED,CORPORATE',
            'data.*.pricingOptions.includedCheckedBagsOnly' => 'nullable|boolean',

            // Validating Airlines
            'data.*.validatingAirlineCodes' => 'nullable|array',
            'data.*.validatingAirlineCodes.*' => 'string',

            // Traveler Pricings
            'data.*.travelerPricings' => 'required|array|min:1',
            'data.*.travelerPricings.*.travelerId' => 'required|string',
            'data.*.travelerPricings.*.fareOption' => 'required|string',
            'data.*.travelerPricings.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'data.*.travelerPricings.*.price.currency' => 'nullable|string',
            'data.*.travelerPricings.*.price.total' => 'nullable|string',
            'data.*.travelerPricings.*.price.base' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment' => 'required|array|min:1',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.segmentId' => 'required|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.cabin' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.fareBasis' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.brandedFare' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.class' => 'nullable|string',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags' => 'nullable|array',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.quantity' => 'nullable|integer',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.weight' => 'nullable|integer',
            'data.*.travelerPricings.*.fareDetailsBySegment.*.includedCheckedBags.weightUnit' => 'nullable|string',

            // Dictionaries (optional — pass through from search response)
            'dictionaries' => 'nullable|array',
            'dictionaries.locations' => 'nullable|array',
            'dictionaries.aircraft' => 'nullable|array',
            'dictionaries.currencies' => 'nullable|array',
            'dictionaries.carriers' => 'nullable|array',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build POST payload
        // Amadeus expects the full search response structure: { data: [...], dictionaries: {...} }
        $payload = [
            'data' => $validated['data'],
        ];

        if (!empty($validated['meta'])) {
            $payload['meta'] = $validated['meta'];
        }

        if (!empty($validated['dictionaries'])) {
            $payload['dictionaries'] = $validated['dictionaries'];
        }

        // 4️⃣ Call Amadeus Flight Choice Prediction API
        $url = $this->baseUrl . '/v2/shopping/flight-offers/prediction';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid flight offers data',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No prediction available for these flight offers',
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to get flight prediction',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFlightDestinations(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'origin' => 'required|string|size:3',
            'departureDate' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'oneWay' => 'nullable|boolean',
            'duration' => 'nullable|string|regex:/^\d{1,3}(,\d{1,3})?$/',  // e.g. "5" or "5,10"
            'nonStop' => 'nullable|boolean',
            'maxPrice' => 'nullable|integer|min:1',
            'viewBy' => 'nullable|in:COUNTRY,DATE,DESTINATION,DURATION,WEEK',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'origin' => strtoupper($validated['origin']),
        ];

        if (!empty($validated['departureDate']))
            $query['departureDate'] = $validated['departureDate'];
        if (!empty($validated['duration']))
            $query['duration'] = $validated['duration'];
        if (!empty($validated['maxPrice']))
            $query['maxPrice'] = $validated['maxPrice'];
        if (!empty($validated['viewBy']))
            $query['viewBy'] = $validated['viewBy'];
        if (!empty($validated['currencyCode']))
            $query['currencyCode'] = strtoupper($validated['currencyCode']);
        if (isset($validated['oneWay']))
            $query['oneWay'] = $validated['oneWay'] ? 'true' : 'false';
        if (isset($validated['nonStop']))
            $query['nonStop'] = $validated['nonStop'] ? 'true' : 'false';

        // 4️⃣ Call Amadeus Flight Destinations API
        $url = $this->baseUrl . '/v1/shopping/flight-destinations';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No destinations found for this origin',
                    'origin' => strtoupper($validated['origin']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight destinations',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFlightDates(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departureDate' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'oneWay' => 'nullable|boolean',
            'duration' => 'nullable|string|regex:/^\d{1,3}(,\d{1,3})?$/', // e.g. "1" or "1,15"
            'nonStop' => 'nullable|boolean',
            'maxPrice' => 'nullable|integer|min:1',
            'viewBy' => 'nullable|in:DATE,DURATION,WEEK',
            'currencyCode' => 'nullable|string|size:3',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'origin' => strtoupper($validated['origin']),
            'destination' => strtoupper($validated['destination']),
        ];

        if (!empty($validated['departureDate']))
            $query['departureDate'] = $validated['departureDate'];
        if (!empty($validated['duration']))
            $query['duration'] = $validated['duration'];
        if (!empty($validated['maxPrice']))
            $query['maxPrice'] = $validated['maxPrice'];
        if (!empty($validated['viewBy']))
            $query['viewBy'] = $validated['viewBy'];
        if (!empty($validated['currencyCode']))
            $query['currencyCode'] = strtoupper($validated['currencyCode']);
        if (isset($validated['oneWay']))
            $query['oneWay'] = $validated['oneWay'] ? 'true' : 'false';
        if (isset($validated['nonStop']))
            $query['nonStop'] = $validated['nonStop'] ? 'true' : 'false';

        // 4️⃣ Call Amadeus Flight Dates API
        $url = $this->baseUrl . '/v1/shopping/flight-dates';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No flight dates found for this route',
                    'route' => strtoupper($validated['origin']) . ' → ' . strtoupper($validated['destination']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight dates',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getFlightAvailabilities(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Origin Destinations
            'originDestinations' => 'required|array|min:1',
            'originDestinations.*.id' => 'required|string',
            'originDestinations.*.originLocationCode' => 'required|string|size:3',
            'originDestinations.*.destinationLocationCode' => 'required|string|size:3',

            // Departure DateTime
            'originDestinations.*.departureDateTime' => 'required|array',
            'originDestinations.*.departureDateTime.date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'originDestinations.*.departureDateTime.time' => 'nullable|date_format:H:i:s',
            'originDestinations.*.departureDateTime.dateWindow' => 'nullable|in:I1D,I2D,I3D,M1D,M2D,M3D,P1D,P2D,P3D',
            'originDestinations.*.departureDateTime.timeWindow' => 'nullable|string|regex:/^\d{1,2}H$/', // e.g. "1H", "3H", "12H"

            // Included/Excluded connections
            'originDestinations.*.includedConnectionPoints' => 'nullable|array',
            'originDestinations.*.includedConnectionPoints.*' => 'string|size:3',
            'originDestinations.*.excludedConnectionPoints' => 'nullable|array',
            'originDestinations.*.excludedConnectionPoints.*' => 'string|size:3',

            // Travelers
            'travelers' => 'required|array|min:1',
            'travelers.*.id' => 'required|string',
            'travelers.*.travelerType' => 'required|in:ADULT,CHILD,HELD_INFANT,SEATED_INFANT,STUDENT',
            'travelers.*.associatedAdultId' => 'nullable|string', // required for HELD_INFANT

            // Sources
            'sources' => 'required|array|min:1',
            'sources.*' => 'string|in:GDS',

            // Search Criteria (optional)
            'searchCriteria' => 'nullable|array',
            'searchCriteria.maxFlightOffers' => 'nullable|integer|min:1|max:250',
            'searchCriteria.oneStopFlight' => 'nullable|boolean',

            // Cabin Restrictions
            'searchCriteria.flightFilters.cabinRestrictions' => 'nullable|array',
            'searchCriteria.flightFilters.cabinRestrictions.*.cabin' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
            'searchCriteria.flightFilters.cabinRestrictions.*.originDestinationIds' => 'nullable|array',
            'searchCriteria.flightFilters.cabinRestrictions.*.originDestinationIds.*' => 'string',

            // Carrier Restrictions
            'searchCriteria.flightFilters.carrierRestrictions.blacklistedInEUAllowed' => 'nullable|boolean',
            'searchCriteria.flightFilters.carrierRestrictions.excludedCarrierCodes' => 'nullable|array',
            'searchCriteria.flightFilters.carrierRestrictions.excludedCarrierCodes.*' => 'string|size:2',
            'searchCriteria.flightFilters.carrierRestrictions.includedCarrierCodes' => 'nullable|array',
            'searchCriteria.flightFilters.carrierRestrictions.includedCarrierCodes.*' => 'string|size:2',

            // Connection Restrictions
            'searchCriteria.flightFilters.connectionRestriction.maxNumberOfConnections' => 'nullable|integer|min:0|max:2',
            'searchCriteria.flightFilters.connectionRestriction.nonStopPreferred' => 'nullable|boolean',
            'searchCriteria.flightFilters.connectionRestriction.airportChangeAllowed' => 'nullable|boolean',
            'searchCriteria.flightFilters.connectionRestriction.technicalStopsAllowed' => 'nullable|boolean',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build POST payload
        $payload = [
            'originDestinations' => array_map(function ($od) {
                $mapped = [
                    'id' => $od['id'],
                    'originLocationCode' => strtoupper($od['originLocationCode']),
                    'destinationLocationCode' => strtoupper($od['destinationLocationCode']),
                    'departureDateTime' => [
                        'date' => $od['departureDateTime']['date'],
                    ],
                ];

                // Append optional datetime fields
                if (!empty($od['departureDateTime']['time'])) {
                    $mapped['departureDateTime']['time'] = $od['departureDateTime']['time'];
                }

                if (!empty($od['departureDateTime']['dateWindow'])) {
                    $mapped['departureDateTime']['dateWindow'] = $od['departureDateTime']['dateWindow'];
                }

                if (!empty($od['departureDateTime']['timeWindow'])) {
                    $mapped['departureDateTime']['timeWindow'] = $od['departureDateTime']['timeWindow'];
                }

                // Append connection points if provided
                if (!empty($od['includedConnectionPoints'])) {
                    $mapped['includedConnectionPoints'] = array_map('strtoupper', $od['includedConnectionPoints']);
                }

                if (!empty($od['excludedConnectionPoints'])) {
                    $mapped['excludedConnectionPoints'] = array_map('strtoupper', $od['excludedConnectionPoints']);
                }

                return $mapped;
            }, $validated['originDestinations']),

            'travelers' => array_map(function ($traveler) {
                $mapped = [
                    'id' => $traveler['id'],
                    'travelerType' => $traveler['travelerType'],
                ];

                // Required for HELD_INFANT — links infant to accompanying adult
                if (!empty($traveler['associatedAdultId'])) {
                    $mapped['associatedAdultId'] = $traveler['associatedAdultId'];
                }

                return $mapped;
            }, $validated['travelers']),

            'sources' => $validated['sources'],
        ];

        // 4️⃣ Append searchCriteria if provided
        if (!empty($validated['searchCriteria'])) {
            $searchCriteria = [];

            if (!empty($validated['searchCriteria']['maxFlightOffers'])) {
                $searchCriteria['maxFlightOffers'] = $validated['searchCriteria']['maxFlightOffers'];
            }

            if (isset($validated['searchCriteria']['oneStopFlight'])) {
                $searchCriteria['oneStopFlight'] = $validated['searchCriteria']['oneStopFlight'];
            }

            if (!empty($validated['searchCriteria']['flightFilters'])) {
                $flightFilters = [];

                // Cabin restrictions
                if (!empty($validated['searchCriteria']['flightFilters']['cabinRestrictions'])) {
                    $flightFilters['cabinRestrictions'] = $validated['searchCriteria']['flightFilters']['cabinRestrictions'];
                }

                // Carrier restrictions
                if (!empty($validated['searchCriteria']['flightFilters']['carrierRestrictions'])) {
                    $flightFilters['carrierRestrictions'] = $validated['searchCriteria']['flightFilters']['carrierRestrictions'];
                }

                // Connection restrictions
                if (!empty($validated['searchCriteria']['flightFilters']['connectionRestriction'])) {
                    $flightFilters['connectionRestriction'] = $validated['searchCriteria']['flightFilters']['connectionRestriction'];
                }

                $searchCriteria['flightFilters'] = $flightFilters;
            }

            $payload['searchCriteria'] = $searchCriteria;
        }

        // 5️⃣ Call Amadeus Flight Availabilities API
        $url = $this->baseUrl . '/v1/shopping/availability/flight-availabilities';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — invalid availability request data',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No flight availabilities found for this route',
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight availabilities',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRecommendedLocations(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'cityCodes' => 'required|string',   // comma-separated IATA city codes e.g. "PAR" or "PAR,LON"
            'travelerCountryCode' => 'nullable|string|size:2',
            'destinationCountryCodes' => 'nullable|string', // comma-separated ISO country codes e.g. "US,CA"
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'cityCodes' => strtoupper($validated['cityCodes']),
        ];

        if (!empty($validated['travelerCountryCode'])) {
            $query['travelerCountryCode'] = strtoupper($validated['travelerCountryCode']);
        }

        if (!empty($validated['destinationCountryCodes'])) {
            $query['destinationCountryCodes'] = strtoupper($validated['destinationCountryCodes']);
        }

        // 4️⃣ Call Amadeus Recommended Locations API
        $url = $this->baseUrl . '/v1/reference-data/recommended-locations';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No recommendations found',
                    'cityCodes' => strtoupper($validated['cityCodes']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve recommended locations',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFlightSchedule(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string|max:4',
            'scheduledDepartureDate' => 'required|date_format:Y-m-d',
            'operationalSuffix' => 'nullable|string|size:1',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'carrierCode' => strtoupper($validated['carrierCode']),
            'flightNumber' => $validated['flightNumber'],
            'scheduledDepartureDate' => $validated['scheduledDepartureDate'],
        ];

        if (!empty($validated['operationalSuffix'])) {
            $query['operationalSuffix'] = strtoupper($validated['operationalSuffix']);
        }

        // 4️⃣ Call Amadeus Flight Schedule API
        $url = $this->baseUrl . '/v2/schedule/flights';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'Flight schedule not found',
                    'carrierCode' => strtoupper($validated['carrierCode']),
                    'flightNumber' => $validated['flightNumber'],
                    'date' => $validated['scheduledDepartureDate'],
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight schedule',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function predictFlightDelay(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'originLocationCode' => 'required|string|size:3',
            'destinationLocationCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m-d',
            'departureTime' => 'required|date_format:H:i:s',
            'arrivalDate' => 'required|date_format:Y-m-d|after_or_equal:departureDate',
            'arrivalTime' => 'required|date_format:H:i:s',
            'aircraftCode' => 'required|string|max:4',
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string|max:4',
            'duration' => 'required|string|regex:/^PT(\d+H)?(\d+M)?$/', // ISO 8601 e.g. PT31H10M
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'originLocationCode' => strtoupper($validated['originLocationCode']),
            'destinationLocationCode' => strtoupper($validated['destinationLocationCode']),
            'departureDate' => $validated['departureDate'],
            'departureTime' => $validated['departureTime'],
            'arrivalDate' => $validated['arrivalDate'],
            'arrivalTime' => $validated['arrivalTime'],
            'aircraftCode' => strtoupper($validated['aircraftCode']),
            'carrierCode' => strtoupper($validated['carrierCode']),
            'flightNumber' => $validated['flightNumber'],
            'duration' => $validated['duration'],
        ];

        // 4️⃣ Call Amadeus Flight Delay Prediction API
        $url = $this->baseUrl . '/v1/travel/predictions/flight-delay';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No delay prediction available for this flight',
                    'flightNumber' => strtoupper($validated['carrierCode']) . $validated['flightNumber'],
                    'date' => $validated['departureDate'],
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve flight delay prediction',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function predictAirportOnTime(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'airportCode' => 'required|string|size:3',
            'date' => 'required|date_format:Y-m-d',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'airportCode' => strtoupper($validated['airportCode']),
            'date' => $validated['date'],
        ];

        // 4️⃣ Call Amadeus Airport On-Time Prediction API
        $url = $this->baseUrl . '/v1/airport/predictions/on-time';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No on-time prediction available for this airport',
                    'airportCode' => strtoupper($validated['airportCode']),
                    'date' => $validated['date'],
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve airport on-time prediction',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCheckinLinks(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'airlineCode' => 'required|string|size:2',
            'language' => 'nullable|string|max:5', // e.g. "en-GB", "fr-FR", "es-ES"
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'airlineCode' => strtoupper($validated['airlineCode']),
        ];

        if (!empty($validated['language'])) {
            $query['language'] = $validated['language'];
        }

        // 4️⃣ Call Amadeus Checkin Links API
        $url = $this->baseUrl . '/v2/reference-data/urls/checkin-links';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No check-in links found for this airline',
                    'airlineCode' => strtoupper($validated['airlineCode']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve check-in links',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAirlines(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            // Comma-separated IATA or ICAO airline codes e.g. "BA" or "BA,AIC,AIE"
            'airlineCodes' => 'required|string|regex:/^[A-Za-z0-9]{2,3}(,[A-Za-z0-9]{2,3})*$/',
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'airlineCodes' => strtoupper($validated['airlineCodes']),
        ];

        // 4️⃣ Call Amadeus Airlines Reference Data API
        $url = $this->baseUrl . '/v1/reference-data/airlines';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your airline codes format',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No airlines found for the provided codes',
                    'airlineCodes' => strtoupper($validated['airlineCodes']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve airline data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAirlineDestinations(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'airlineCode' => 'required|string|size:2',
            'max' => 'nullable|integer|min:1|max:500',
            'arrivalCountryCode' => 'nullable|string|size:2',  // ISO country code filter
        ]);

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'airlineCode' => strtoupper($validated['airlineCode']),
        ];

        if (!empty($validated['max'])) {
            $query['max'] = $validated['max'];
        }

        if (!empty($validated['arrivalCountryCode'])) {
            $query['arrivalCountryCode'] = strtoupper($validated['arrivalCountryCode']);
        }

        // 4️⃣ Call Amadeus Airline Destinations API
        $url = $this->baseUrl . '/v1/airline/destinations';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No destinations found for this airline',
                    'airlineCode' => strtoupper($validated['airlineCode']),
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve airline destinations',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchLocations(Request $request)
    {
        // 1️⃣ Validate incoming request
        $validated = $request->validate([
            'keyword' => 'required|string|min:1|max:20',
            'subType' => 'required|string',                          // comma-separated: CITY,AIRPORT,ANY
            'countryCode' => 'nullable|string|size:2',                   // ISO 3166-1 alpha-2
            'pageLimit' => 'nullable|integer|min:1|max:10',
            'pageOffset' => 'nullable|integer|min:0',
            'sort' => 'nullable|in:analytics.travelers.score,iataCode,name,relevance',
            'view' => 'nullable|in:FULL,LIGHT',                   // LIGHT = iataCode + name only
        ]);

        // 2️⃣ Validate subType values individually
        $allowedSubTypes = ['CITY', 'AIRPORT', 'ANY'];
        $subTypes = array_map('trim', explode(',', strtoupper($validated['subType'])));

        foreach ($subTypes as $subType) {
            if (!in_array($subType, $allowedSubTypes)) {
                return response()->json([
                    'error' => 'Invalid subType value: ' . $subType,
                    'allowed' => $allowedSubTypes,
                ], 422);
            }
        }

        // 2️⃣ Get Bearer token
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        // 3️⃣ Build query params
        $query = [
            'subType' => implode(',', $subTypes),
            'keyword' => $validated['keyword'],
        ];

        if (!empty($validated['countryCode']))
            $query['countryCode'] = strtoupper($validated['countryCode']);
        if (!empty($validated['pageLimit']))
            $query['page[limit]'] = $validated['pageLimit'];
        if (!empty($validated['pageOffset']))
            $query['page[offset]'] = $validated['pageOffset'];
        if (!empty($validated['sort']))
            $query['sort'] = $validated['sort'];
        if (!empty($validated['view']))
            $query['view'] = $validated['view'];

        // 4️⃣ Call Amadeus Locations API
        $url = $this->baseUrl . '/v1/reference-data/locations';

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

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                400 => response()->json([
                    'error' => 'Bad request — check your query parameters',
                    'details' => $body,
                ], 400),

                401 => response()->json([
                    'error' => 'Unauthorized — invalid or expired token',
                ], 401),

                404 => response()->json([
                    'error' => 'No locations found',
                    'keyword' => $validated['keyword'],
                ], 404),

                default => response()->json([
                    'error' => 'Amadeus API error',
                    'details' => $body,
                ], $statusCode),
            };

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response()->json(['error' => 'Amadeus server error'], 502);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to search locations',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLocation(string $locationId)
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Unable to get access token'], 500);
        }

        $url = $this->baseUrl . '/v1/reference-data/locations/' . strtoupper($locationId);

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody(), true);

            return match ($statusCode) {
                401 => response()->json(['error' => 'Unauthorized'], 401),
                404 => response()->json(['error' => 'Location not found', 'locationId' => $locationId], 404),
                default => response()->json(['error' => 'Amadeus API error', 'details' => $body], $statusCode),
            };

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

        $token = $this->getToken();

        $query = [
            'subType' => 'CITY,AIRPORT',
            'keyword' => $validated['q'],
            'page[limit]' => 7,              // sweet spot for dropdown lists
            'sort' => 'analytics.travelers.score',
            'view' => 'LIGHT',
        ];

        if (!empty($validated['countryCode'])) {
            $query['countryCode'] = strtoupper($validated['countryCode']);
        }

        $response = $this->client->get($this->baseUrl . '/v1/reference-data/locations', [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
        ]);

        $data = json_decode($response->getBody(), true);

        // Shape for dropdown consumption
        $suggestions = array_map(fn($loc) => [
            'id' => $loc['id'],
            'label' => $loc['name'] . ' (' . $loc['iataCode'] . ')',
            'iata' => $loc['iataCode'],
            'subType' => $loc['subType'],
        ], $data['data'] ?? []);

        return response()->json($suggestions);
    }




}
