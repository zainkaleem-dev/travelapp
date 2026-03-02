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

}
