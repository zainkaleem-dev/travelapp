<?php

namespace App\Livewire\Flightslist;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\FlightApiController;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Services\AmadeusService;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
#[Layout("layouts.flight")]
class FlightList extends Component
{
    // ─── Search Form ──────────────────────────────────────────────
    public string $origin = "";
    public string $originIata = "";
    public string $destination = "";
    public string $destIata = "";
    public string $departDate = "";
    public string $returnDate = "";

    public string $tripType = "return";
    public array $multiFlights = [];

    public bool $isMulti = false;
    public array $segments = [];

    public int $passengers = 1;

    // Passenger breakdown
    public int $adultCount = 1;
    public int $childCount = 0;
    public int $infantCount = 0;

    public string $travelClass = "Economy Class";
    public string $travelClassEnum = "ECONOMY";

    // ─── Filters ──────────────────────────────────────────────────
    public int $priceMin = 100;
    public int $priceMax = 500000;
    public array $stops = ["any"];
    public array $airlines = ["any"];
    public array $departTimes = ["any"];

    // ─── Sorting / Date ──────────────────────────────────────────
    public string $sortTab = "cheap";
    public string $selectedDate = "2026-07-14";

    public bool $loadingPrices = false;
    public string $currencyCode = "USD";

    public array $allFlights = [];
    public ?string $errorMessage = null;
    public array $fareDetails = [];
    public array $loadingFares = [];

    // ─── Date rail (7 days around selected date) ──────────────────
    public array $dateRail = [];

    public function initDateRail()
    {
        if ($this->isMulti || empty($this->departDate)) {
            return;
        }

        // Sync selectedDate with current departDate if not already set
        if (
            empty($this->selectedDate) ||
            $this->selectedDate === "2026-07-14"
        ) {
            $this->selectedDate = $this->departDate;
        }

        // 1. Generate the 10 days (-4 days / +5 days around departDate)
        $centerDate = new \DateTime($this->departDate);
        $startDate = (clone $centerDate)->modify("-4 days");

        $this->dateRail = [];
        for ($i = 0; $i < 10; $i++) {
            $current = (clone $startDate)->modify("+$i days");
            $dateStr = $current->format("Y-m-d");
            $this->dateRail[] = [
                "date" => $dateStr,
                "label" => $current->format("D d.m"),
                "price" => null, // Will populate via API
            ];
        }

        // Ensure selectedDate is one of these 10 dates if it's currently invalid or outside range
        $dates = array_column($this->dateRail, "date");
        if (!in_array($this->selectedDate, $dates)) {
            $this->selectedDate = $this->departDate;
        }
    }

    #[On("loadDateRailPrices")]
    public function fetchDateRailPrices()
    {
        if (
            $this->isMulti ||
            empty($this->departDate) ||
            empty($this->dateRail)
        ) {
            return;
        }

        if (empty($this->originIata) || empty($this->destIata)) {
            return;
        }

        $this->loadingPrices = true;
        try {
            $amadeus = app(\App\Services\AmadeusService::class);
            $token = $amadeus->getToken();
            $baseUrl = $amadeus->getBaseUrl();

            if (!$token) {
                $this->loadingPrices = false;
                return;
            }

            $responses = \Illuminate\Support\Facades\Http::pool(function (
                \Illuminate\Http\Client\Pool $pool,
            ) use ($token, $baseUrl) {
                $reqs = [];

                // Build common traveler structure
                $travelers = [];
                for ($i = 0; $i < $this->adultCount; $i++) {
                    $travelers[] = [
                        "id" => (string) (count($travelers) + 1),
                        "travelerType" => "ADULT",
                    ];
                }
                for ($i = 0; $i < $this->childCount; $i++) {
                    $travelers[] = [
                        "id" => (string) (count($travelers) + 1),
                        "travelerType" => "CHILD",
                    ];
                }
                for ($i = 0; $i < $this->infantCount; $i++) {
                    $travelers[] = [
                        "id" => (string) (count($travelers) + 1),
                        "travelerType" => "HELD_INFANT",
                    ];
                }

                foreach ($this->dateRail as $index => $railItem) {
                    $originDestinations = [
                        [
                            "id" => "1",
                            "originLocationCode" => strtoupper(
                                $this->originIata,
                            ),
                            "destinationLocationCode" => strtoupper(
                                $this->destIata,
                            ),
                            "departureDateTimeRange" => [
                                "date" => $railItem["date"],
                            ],
                        ],
                    ];

                    if (!empty($this->returnDate)) {
                        $originDestinations[] = [
                            "id" => "2",
                            "originLocationCode" => strtoupper($this->destIata),
                            "destinationLocationCode" => strtoupper(
                                $this->originIata,
                            ),
                            "departureDateTimeRange" => [
                                "date" => $this->returnDate,
                            ],
                        ];
                    }

                    $payload = [
                        "currencyCode" => $this->currencyCode,
                        "originDestinations" => $originDestinations,
                        "travelers" => $travelers,
                        "sources" => ["GDS"],
                        "searchCriteria" => [
                            "maxFlightOffers" => 1,
                            "flightFilters" => [
                                "cabinRestrictions" => [
                                    [
                                        "cabin" => $this->travelClassEnum,
                                        "coverage" => "MOST_SEGMENTS",
                                        "originDestinationIds" => array_map(
                                            fn($od) => $od["id"],
                                            $originDestinations,
                                        ),
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $reqs[] = $pool
                        ->as((string) $index)
                        ->withOptions(["verify" => false, "proxy" => null])
                        ->withToken($token)
                        ->post(
                            $baseUrl . "/v2/shopping/flight-offers",
                            $payload,
                        );
                }
                return $reqs;
            });

            foreach ($responses as $index => $response) {
                if (
                    $response instanceof \Illuminate\Http\Client\Response &&
                    $response->ok()
                ) {
                    $data = $response->json();
                    if (
                        !empty($data["data"]) &&
                        isset($data["data"][0]["price"]["total"])
                    ) {
                        $this->dateRail[$index]["price"] =
                            $data["data"][0]["price"]["total"];

                        if (isset($data["data"][0]["price"]["currency"])) {
                            $apiCurrency =
                                $data["data"][0]["price"]["currency"];
                            if (empty($this->currencyCode)) {
                                $this->currencyCode = $apiCurrency;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Date rail price fetch error: " . $e->getMessage());
        } finally {
            $this->loadingPrices = false;
        }
    }

    /**
     * Sync the selected date's price in the date rail with the actual
     * cheapest price from the loaded flight list, so they always match.
     */
    protected function syncDateRailWithFlights(): void
    {
        if (empty($this->allFlights) || empty($this->dateRail)) {
            return;
        }

        $cheapest = min(array_column($this->allFlights, "price"));

        foreach ($this->dateRail as &$day) {
            if ($day["date"] === $this->selectedDate) {
                $day["price"] = $cheapest;
                break;
            }
        }
    }

    // ─── Computed: filtered + sorted flights ──────────────────────
    #[Computed]
    public function flights(): array
    {
        if (empty($this->allFlights)) {
            \Illuminate\Support\Facades\Log::warning(
                "FLIGHTS_COMPUTED_BUT_ALLFLIGHTS_EMPTY",
            );
        }
        $results = array_filter($this->allFlights, function ($f) {
            // Price range
            if (
                $f["price"] < $this->priceMin ||
                $f["price"] > $this->priceMax
            ) {
                return false;
            }
            // Airlines filter
            if (
                !in_array("any", $this->airlines) &&
                !in_array(strtolower($f["airline"]), $this->airlines)
            ) {
                return false;
            }
            // Stops filter
            if (!in_array("any", $this->stops)) {
                $isDirect = strtolower($f["stops"]) === "direct";
                if (in_array("direct", $this->stops) && !$isDirect) {
                    return false;
                }
                if (in_array("1stop", $this->stops) && $isDirect) {
                    return false;
                }
            }
            // Refundable filter (for the tab)
            if ($this->sortTab === "refundable" && !$f["refundable"]) {
                return false;
            }
            return true;
        });

        // Numerical Sorts
        switch ($this->sortTab) {
            case "cheap":
                usort(
                    $results,
                    fn($a, $b) => (float) $a["price"] <=> (float) $b["price"],
                );
                break;
            case "fastest":
                usort(
                    $results,
                    fn($a, $b) => (int) $a["durationMinutes"] <=>
                        (int) $b["durationMinutes"],
                );
                break;
            case "early":
                usort(
                    $results,
                    fn($a, $b) => (int) $a["departureTimestamp"] <=>
                        (int) $b["departureTimestamp"],
                );
                break;
            case "late":
                usort(
                    $results,
                    fn($a, $b) => (int) $b["departureTimestamp"] <=>
                        (int) $a["departureTimestamp"],
                );
                break;
            case "best":
                // Smart Sort: Weighted score of price and duration
                if (!empty($results)) {
                    $prices = array_column($results, "price");
                    $durs = array_column($results, "durationMinutes");

                    $minPrice = min($prices) ?: 1;
                    $minDur = min($durs) ?: 1;

                    usort($results, function ($a, $b) use ($minPrice, $minDur) {
                        // We give 70% weight to price and 30% to duration
                        $scoreA =
                            ((float) $a["price"] / $minPrice) * 0.7 +
                            ((int) $a["durationMinutes"] / $minDur) * 0.3;
                        $scoreB =
                            ((float) $b["price"] / $minPrice) * 0.7 +
                            ((int) $b["durationMinutes"] / $minDur) * 0.3;
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
            ->pluck("airline")
            ->unique()
            ->values()
            ->toArray();
    }

    public function setSort(string $tab): void
    {
        $this->sortTab = $tab;
    }

    public function clearFilters(): void
    {
        $this->priceMin = 100;
        $this->priceMax = 500000;
        $this->stops = ["any"];
        $this->airlines = ["any"];
        $this->departTimes = ["any"];
    }

    private static function travelClassToEnum(string $class): string
    {
        $map = [
            "Economy Class" => "ECONOMY",
            "Premium Economy" => "PREMIUM_ECONOMY",
            "Business Class" => "BUSINESS",
            "First Class" => "FIRST",
        ];
        return $map[$class] ?? "ECONOMY";
    }

    public function updatedTravelClass(): void
    {
        $this->travelClassEnum = self::travelClassToEnum($this->travelClass);

        $params = session("flight_search_params", []);
        $params["travelClass"] = $this->travelClass;
        $params["travelClassEnum"] = $this->travelClassEnum;
        session(["flight_search_params" => $params]);

        $this->allFlights = [];
        $this->dispatch("loadFlights");
        $this->dispatch("loadDateRailPrices");
    }

    public function setTravelClass(string $class): void
    {
        $this->travelClass = $class;
        $this->travelClassEnum = self::travelClassToEnum($class);

        $params = session("flight_search_params", []);
        $params["travelClass"] = $this->travelClass;
        $params["travelClassEnum"] = $this->travelClassEnum;
        session(["flight_search_params" => $params]);

        $this->allFlights = [];
        $this->dispatch("loadFlights");
        $this->dispatch("loadDateRailPrices");
    }

    protected function syncPassengersTotal(): void
    {
        $this->passengers =
            $this->adultCount + $this->childCount + $this->infantCount;
    }

    public function mount()
    {
        $params = session("flight_search_params");

        if (!$params) {
            return redirect()->route("flight-search");
        }

        $this->tripType =
            $params["tripType"] ??
            ($params["isMulti"]
                ? "multi"
                : (!empty($params["returnDate"])
                    ? "return"
                    : "oneway"));
        $this->origin = $params["origin"] ?? "";
        $this->originIata = $params["originIata"] ?? "";
        $this->destination = $params["destination"] ?? "";
        $this->destIata = $params["destIata"] ?? "";
        $this->departDate = $params["departDate"] ?? "";
        $this->returnDate = $params["returnDate"] ?? "";
        $this->isMulti = $params["isMulti"] ?? false;
        $this->segments = $params["segments"] ?? [];

        if ($this->isMulti && !empty($this->segments)) {
            $this->multiFlights = array_map(function ($s) {
                return [
                    "dep" => $s["origin"],
                    "arr" => $s["destination"],
                    "originIata" => $s["originIata"],
                    "destIata" => $s["destIata"],
                    "date" => $s["date"],
                ];
            }, $this->segments);
        }

        $this->adultCount = $params["adultCount"] ?? 1;
        $this->childCount = $params["childCount"] ?? 0;
        $this->infantCount = $params["infantCount"] ?? 0;
        $this->travelClass = $params["travelClass"] ?? "Economy Class";
        $this->travelClassEnum = $params["travelClassEnum"] ?? "ECONOMY";
        $this->currencyCode = $params["currency"] ?? "USD";

        $this->syncPassengersTotal();
        $this->initDateRail();
        $this->loadFlights();
        $this->fetchDateRailPrices();
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->departDate = $date;

        // Sync back to session
        $params = session("flight_search_params", []);
        $params["departDate"] = $this->departDate;
        session(["flight_search_params" => $params]);

        $this->initDateRail();

        // ─── THE FIX: Dispatch separately to avoid blocking one request ──
        $this->allFlights = []; // Clear current results to show loading
        $this->dispatch("loadFlights");
        $this->dispatch("loadDateRailPrices");
    }

    public function shiftDate(int $days): void
    {
        if (empty($this->departDate)) {
            return;
        }

        $date = new \DateTime($this->departDate);
        $date->modify(($days > 0 ? "+" : "-") . abs($days) . " days");

        // Don't allow past dates
        if ($date < new \DateTime("today")) {
            $date = new \DateTime("today");
        }

        $newDate = $date->format("Y-m-d");
        $this->selectDate($newDate);
    }

    #[On("loadFlights")]
    public function loadFlights(): void
    {
        $this->errorMessage = null;

        $amadeusService = app(AmadeusService::class);
        $controller = new FlightApiController($amadeusService);

        $originDestinations = [];

        if ($this->isMulti && !empty($this->segments)) {
            foreach ($this->segments as $index => $segment) {
                $originDestinations[] = [
                    "id" => (string) ($index + 1),
                    "originLocationCode" => strtoupper($segment["originIata"]),
                    "destinationLocationCode" => strtoupper(
                        $segment["destIata"],
                    ),
                    "departureDateTimeRange" => [
                        "date" => $segment["date"],
                    ],
                ];
            }
        } else {
            // One-way or Return
            $originDestinations[] = [
                "id" => "1",
                "originLocationCode" => strtoupper($this->originIata),
                "destinationLocationCode" => strtoupper($this->destIata),
                "departureDateTimeRange" => [
                    "date" => $this->departDate,
                ],
            ];

            if (!empty($this->returnDate)) {
                $originDestinations[] = [
                    "id" => "2",
                    "originLocationCode" => strtoupper($this->destIata),
                    "destinationLocationCode" => strtoupper($this->originIata),
                    "departureDateTimeRange" => [
                        "date" => $this->returnDate,
                    ],
                ];
            }
        }

        $travelers = [];
        for ($i = 0; $i < $this->adultCount; $i++) {
            $travelers[] = [
                "id" => (string) (count($travelers) + 1),
                "travelerType" => "ADULT",
            ];
        }
        for ($i = 0; $i < $this->childCount; $i++) {
            $travelers[] = [
                "id" => (string) (count($travelers) + 1),
                "travelerType" => "CHILD",
            ];
        }
        for ($i = 0; $i < $this->infantCount; $i++) {
            $travelers[] = [
                "id" => (string) (count($travelers) + 1),
                "travelerType" => "HELD_INFANT",
            ];
        }

        $request = new Request();
        $request->merge([
            "originDestinations" => $originDestinations,
            "travelers" => $travelers,
            "sources" => ["GDS"],
            "currencyCode" => $this->currencyCode,
            "searchCriteria" => [
                "flightFilters" => [
                    "cabinRestrictions" => [
                        [
                            "cabin" => $this->travelClassEnum,
                            "coverage" => "MOST_SEGMENTS",
                            "originDestinationIds" => array_map(
                                fn($od) => $od["id"],
                                $originDestinations,
                            ),
                        ],
                    ],
                ],
            ],
        ]);

        $response = $controller->searchFlightsPost($request);

        $statusCode = $response->getStatusCode();
        $data = json_decode($response->getContent(), true);

        if ($statusCode === 200 && isset($data["data"])) {
            $mappedFlights = [];
            $dictionaries = $data["dictionaries"] ?? [];

            // Update currency from API response if ours is missing or different
            if (
                !empty($data["data"]) &&
                isset($data["data"][0]["price"]["currency"])
            ) {
                $apiCurrency = $data["data"][0]["price"]["currency"];
                if (
                    empty($this->currencyCode) ||
                    $this->currencyCode !== $apiCurrency
                ) {
                    $this->currencyCode = $apiCurrency;
                }
            }

            foreach ($data["data"] as $index => $offer) {
                // For simplified multi-city view, we use the first itinerary's first segment for departure
                // and the last itinerary's last segment for arrival
                $itineraries = $offer["itineraries"];
                $firstItinerary = $itineraries[0] ?? null;
                $lastItinerary = end($itineraries);

                $firstSegment = $firstItinerary["segments"][0] ?? null;
                $lastSegment = end($lastItinerary["segments"]);

                if (!$firstSegment) {
                    continue;
                }

                $seats = $offer["numberOfBookableSeats"] ?? null;
                $travelerPricing = $offer["travelerPricings"][0] ?? [];
                $fareDetailsBySegment = collect(
                    $travelerPricing["fareDetailsBySegment"] ?? [],
                );

                $carrierCode = $firstSegment["carrierCode"];
                $airlineName =
                    $dictionaries["carriers"][$carrierCode] ?? $carrierCode;

                $depTime = date(
                    "H:i",
                    strtotime($firstSegment["departure"]["at"]),
                );
                $arrTime = date(
                    "H:i",
                    strtotime($lastSegment["arrival"]["at"]),
                );

                // Total duration across all itineraries
                $totalDurationMinutes = 0;
                foreach ($itineraries as $itin) {
                    $durationRaw = $itin["duration"] ?? "";
                    if (
                        preg_match(
                            "/PT(?:(\d+)H)?(?:(\d+)M)?/",
                            $durationRaw,
                            $matches,
                        )
                    ) {
                        $hours = (int) ($matches[1] ?? 0);
                        $minutes = (int) ($matches[2] ?? 0);
                        $totalDurationMinutes += $hours * 60 + $minutes;
                    }
                }

                $h = floor($totalDurationMinutes / 60);
                $m = $totalDurationMinutes % 60;
                $duration = "{$h}h {$m}m";

                $price = $offer["price"]["total"] ?? "0";
                $basePrice = $offer["price"]["base"] ?? "0";

                $stopsCount = 0;
                foreach ($itineraries as $itin) {
                    $stopsCount += count($itin["segments"]) - 1;
                }
                $stopsLabel = $this->isMulti
                    ? "Multi-city"
                    : ($stopsCount === 0
                        ? "Direct"
                        : $stopsCount . " Stop" . ($stopsCount > 1 ? "s" : ""));

                // Calculate first departure timestamp for sorting
                $departureTimestamp = strtotime(
                    $firstSegment["departure"]["at"],
                );
                $refundable = !(
                    $offer["pricingOptions"]["noRefundFare"] ?? false
                );
                $flexible =
                    $offer["pricingOptions"]["noRestrictionFare"] ?? false;

                // Price Breakdown
                $totalPrice = (float) ($offer["price"]["total"] ?? 0);
                $basePrice = (float) ($offer["price"]["base"] ?? 0);
                $taxAmount = $totalPrice - $basePrice;

                // Map itineraries individually for frontend
                $mappedItineraries = [];
                foreach ($itineraries as $idx => $itin) {
                    $itinFirstSeg = $itin["segments"][0];
                    $itinLastSeg = end($itin["segments"]);

                    $itinDepTime = date(
                        "H:i",
                        strtotime($itinFirstSeg["departure"]["at"]),
                    );
                    $itinArrTime = date(
                        "H:i",
                        strtotime($itinLastSeg["arrival"]["at"]),
                    );

                    $itinDurationRaw = $itin["duration"] ?? "";
                    $itinDuration = "";
                    if (
                        preg_match(
                            "/PT(?:(\d+)H)?(?:(\d+)M)?/",
                            $itinDurationRaw,
                            $matches,
                        )
                    ) {
                        $hours = (int) ($matches[1] ?? 0);
                        $minutes = (int) ($matches[2] ?? 0);
                        $itinDuration = "{$hours}h {$minutes}m";
                    }

                    $itinStopsCount = count($itin["segments"]) - 1;
                    $itinStopsLabel =
                        $itinStopsCount === 0
                            ? "Direct"
                            : $itinStopsCount .
                                " Stop" .
                                ($itinStopsCount > 1 ? "s" : "");

                    $itinCarrierCode = $itinFirstSeg["carrierCode"];
                    $itinAirlineName =
                        $dictionaries["carriers"][$itinCarrierCode] ??
                        $itinCarrierCode;

                    // Codeshare / Operating Airline
                    $operatingCarrierCode =
                        $itinFirstSeg["operating"]["carrierCode"] ??
                        $itinCarrierCode;
                    $operatingAirlineName =
                        $dictionaries["carriers"][$operatingCarrierCode] ??
                        $operatingCarrierCode;
                    $isCodeshare = $operatingCarrierCode !== $itinCarrierCode;

                    // Aircraft Info
                    $aircraftCode = $itinFirstSeg["aircraft"]["code"] ?? "";
                    $aircraftName =
                        $dictionaries["aircraft"][$aircraftCode] ??
                        $aircraftCode;

                    // Technical Stops
                    $techStops = $itinFirstSeg["numberTechnicalStops"] ?? 0;

                    // Extract baggage and amenities for this itinerary
                    $segId = $itinFirstSeg["id"];
                    $fareDetail = $fareDetailsBySegment->firstWhere(
                        "segmentId",
                        $segId,
                    );
                    $baggage = "No checked bags";
                    $itinAmenities = [];

                    if ($fareDetail) {
                        // Baggage
                        if (isset($fareDetail["includedCheckedBags"])) {
                            $qty =
                                $fareDetail["includedCheckedBags"][
                                    "quantity"
                                ] ?? null;
                            $weight =
                                $fareDetail["includedCheckedBags"]["weight"] ??
                                null;
                            if ($qty !== null) {
                                $baggage =
                                    $qty .
                                    " Check-in bag" .
                                    ($qty > 1 ? "s" : "");
                            } elseif ($weight !== null) {
                                $baggage =
                                    $weight .
                                    ($fareDetail["includedCheckedBags"][
                                        "weightUnit"
                                    ] ??
                                        "KG") .
                                    " Check-in bag";
                            }
                        }

                        // Amenities
                        $rawAmenities = $fareDetail["amenities"] ?? [];
                        foreach ($rawAmenities as $ram) {
                            $itinAmenities[] = $ram["description"] ?? "Amenity";
                        }
                    }

                    if ($this->isMulti && isset($this->segments[$idx])) {
                        $itinDepCity = $this->segments[$idx]["origin"] ?? "";
                        $itinArrCity =
                            $this->segments[$idx]["destination"] ?? "";
                        $itinDepCity = trim(
                            preg_replace("/\([A-Z]{3}\)/", "", $itinDepCity),
                        );
                        $itinArrCity = trim(
                            preg_replace("/\([A-Z]{3}\)/", "", $itinArrCity),
                        );
                    } else {
                        // Return or Oneway
                        if ($idx === 0) {
                            $itinDepCity = trim(
                                preg_replace(
                                    "/\([A-Z]{3}\)/",
                                    "",
                                    $this->origin,
                                ),
                            );
                            $itinArrCity = trim(
                                preg_replace(
                                    "/\([A-Z]{3}\)/",
                                    "",
                                    $this->destination,
                                ),
                            );
                        } else {
                            // Keep in mind for Oneway there is no $idx = 1
                            $itinDepCity = trim(
                                preg_replace(
                                    "/\([A-Z]{3}\)/",
                                    "",
                                    $this->destination,
                                ),
                            );
                            $itinArrCity = trim(
                                preg_replace(
                                    "/\([A-Z]{3}\)/",
                                    "",
                                    $this->origin,
                                ),
                            );
                        }
                    }

                    $mappedItineraries[] = [
                        "dep" => $itinDepTime,
                        "arr" => $itinArrTime,
                        "depCity" => $itinDepCity,
                        "arrCity" => $itinArrCity,
                        "depAirport" => $itinFirstSeg["departure"]["iataCode"],
                        "arrAirport" => $itinLastSeg["arrival"]["iataCode"],
                        "duration" => $itinDuration,
                        "stops" => $itinStopsLabel,
                        "airline" => $itinAirlineName,
                        "airlineCode" => $itinCarrierCode,
                        "flightNumber" =>
                            $itinCarrierCode .
                            ($itinFirstSeg["number"] ??
                                ($itinFirstSeg["flightNumber"] ?? "")),
                        "airlineColor" => "bg-emerald-700",
                        "baggage" => $baggage,
                        "aircraft" => $aircraftName,
                        "operatingCarrier" => $isCodeshare
                            ? $operatingAirlineName
                            : null,
                        "technicalStops" => $techStops,
                        "amenities" => $itinAmenities,
                    ];
                }

                // Removed Cache usage as per user request. We'll store it in the array.
                // Note: This adds to Livewire payload size.
                // $cacheKey = 'flight_offer_' . session()->getId() . '_' . $offer['id'];
                // Cache::put($cacheKey, $offer, now()->addHours(1));

                $mappedFlights[] = [
                    "id" => $offer["id"],
                    "airline" => $airlineName,
                    "airlineCode" => $carrierCode,
                    "airlineColor" => "bg-emerald-700",
                    "dep" => $depTime,
                    "arr" => $arrTime,
                    "depCity" => $this->isMulti
                        ? $this->segments[0]["origin"] ?? "Multi-city"
                        : $this->origin,
                    "depAirport" => $firstSegment["departure"]["iataCode"],
                    "arrCity" => $this->isMulti
                        ? end($this->segments)["destination"] ?? "Multi-city"
                        : $this->destination,
                    "arrAirport" => $lastSegment["arrival"]["iataCode"],
                    "duration" => $duration,
                    "durationMinutes" => $totalDurationMinutes,
                    "departureTimestamp" => $departureTimestamp,
                    "stops" => $stopsLabel,
                    "price" => $price,
                    "basePrice" => $basePrice,
                    "oldPrice" => null,
                    "badge" => null,
                    "badgeClass" => "",
                    "btnClass" => "bg-blue-600 hover:bg-blue-700",
                    "bgClass" => "",
                    "note" => null,
                    "refundable" => $refundable,
                    "flexible" => $flexible,
                    "priceBreakdown" => [
                        "base" => $basePrice,
                        "taxes" => $taxAmount,
                    ],
                    "seats" => $seats,
                    "rawOffer" => $offer,
                    "itineraries" => $mappedItineraries,
                ];
            }

            // Sort by price (cheapest first) and tag the cheapest
            if (!empty($mappedFlights)) {
                usort(
                    $mappedFlights,
                    fn($a, $b) => $a["price"] <=> $b["price"],
                );
                $mappedFlights[0]["badge"] = "Cheapest";
                $mappedFlights[0]["badgeClass"] = "cheapest-badge";
            }

            $this->allFlights = $mappedFlights;

            // Handle scenario where request returned 200, but array holds 0 items.
            if (empty($mappedFlights)) {
                $this->errorMessage =
                    "No flights available for this route on the selected date.";
            }
        } else {
            // Write to error message property
            $this->allFlights = [];

            if (isset($data["error"])) {
                $this->errorMessage = $data["error"]; // Controller passed it via proxy
            } elseif (isset($data["errors"]) && is_array($data["errors"])) {
                $this->errorMessage =
                    $data["errors"][0]["detail"] ??
                    "Amadeus API returned an error processing your request.";
            } else {
                $this->errorMessage =
                    "We could not fetch flights at this time. Please try again later.";
            }
        }
    }

    public function loadFareDetails($id): void
    {
        // Avoid redundant calls
        if (isset($this->fareDetails[$id])) {
            return;
        }
        $this->loadingFares[$id] = true;

        // Find the flight in our current list
        $flight = collect($this->allFlights)->firstWhere("id", $id);
        $rawOffer = $flight["rawOffer"] ?? null;

        if (!$flight || !$rawOffer) {
            $this->loadingFares[$id] = false;
            return;
        }

        try {
            $amadeus = app(\App\Services\AmadeusService::class);
            $response = $amadeus->upsellFlightOffers($rawOffer);

            if (isset($response["data"]) && !empty($response["data"])) {
                $this->fareDetails[$id] = $this->parseUpsellOffers(
                    $response["data"],
                );
            }
        } catch (\Exception $e) {
            // Error handling
        }

        $this->loadingFares[$id] = false;
    }

    private function parseUpsellOffers(array $offers): array
    {
        $grouped = [];

        foreach ($offers as $offer) {
            $price = (float) ($offer["price"]["total"] ?? 0);
            $basePrice = (float) ($offer["price"]["base"] ?? 0);

            // Getting fare details from the first segment of the first traveler
            $fareDetails =
                $offer["travelerPricings"][0]["fareDetailsBySegment"][0] ?? [];

            $cabin = $fareDetails["cabin"] ?? "ECONOMY";
            $brandedFare = $fareDetails["brandedFare"] ?? "STANDARD";

            // Bags
            $bags = $fareDetails["includedCheckedBags"]["quantity"] ?? null;
            $weight = $fareDetails["includedCheckedBags"]["weight"] ?? null;
            $bagString = "No Checked Bags";
            if ($bags !== null) {
                $bagString = $bags . " Checked Bag" . ($bags > 1 ? "s" : "");
            } elseif ($weight !== null) {
                $bagString = $weight . "kg Checked Bag";
            }

            // Amenities
            $amenitiesRaw = $fareDetails["amenities"] ?? [];
            $amenitiesList = [];
            foreach ($amenitiesRaw as $am) {
                if (isset($am["description"])) {
                    $amenitiesList[] = ucwords(
                        strtolower(str_replace("_", " ", $am["description"])),
                    );
                } elseif (isset($am["amenityType"])) {
                    $amenitiesList[] = ucwords(
                        strtolower(str_replace("_", " ", $am["amenityType"])),
                    );
                }
            }

            if (empty($amenitiesList)) {
                $amenitiesList[] = "Standard Seat";
            }

            if (!isset($grouped[$cabin])) {
                $grouped[$cabin] = [];
            }

            // Check if we already have this exact brandedfare in this cabin. If so, only keep the cheapest.
            // (Sometimes Amadeus returns multiple identical bundles with slight routing differences).
            $existingIndex = null;
            foreach ($grouped[$cabin] as $idx => $existing) {
                if ($existing["name"] === $brandedFare) {
                    $existingIndex = $idx;
                    break;
                }
            }

            $formatted = [
                "id" => $offer["id"],
                "name" => str_replace("_", " ", $brandedFare),
                "price" => $price,
                "basePrice" => $basePrice,
                "bags" => $bagString,
                "amenities" => array_slice($amenitiesList, 0, 4), // keep it short for UI
                "raw" => $offer, // store this if we want to submit the exact upsold offer to booking later
            ];

            if ($existingIndex !== null) {
                if ($price < $grouped[$cabin][$existingIndex]["price"]) {
                    $grouped[$cabin][$existingIndex] = $formatted;
                }
            } else {
                $grouped[$cabin][] = $formatted;
            }
        }

        // Sort each cabin by price ascending
        foreach ($grouped as $cabin => &$fares) {
            usort($fares, fn($a, $b) => $a["price"] <=> $b["price"]);
        }

        return $grouped;
    }

    public function selectFlight($id)
    {
        Log::info("SELECT_FLIGHT_STARTED", [
            "flight_id" => $id,
            "allFlights_count" => count($this->allFlights),
        ]);

        // Find the flight in our current list
        $flight = collect($this->allFlights)->firstWhere("id", $id);

        if (!$flight) {
            Log::error("SELECT_FLIGHT_NOT_FOUND", ["flight_id" => $id]);
            session()->flash(
                "error",
                "The selected flight could not be found. Please refresh and try again.",
            );
            return;
        }

        $rawOffer = $flight["rawOffer"] ?? null;

        // Inject the raw offer back before sending it to session for booking
        if ($rawOffer) {
            $flight["rawOffer"] = $rawOffer;
        }

        // Also capture the parsed fare tiers if the user opened the dropdown
        $fareTiers = $this->fareDetails[$id] ?? [];

        // If the user clicked Select without ever opening the dropdown, we must fetch the tiers now.
        if (empty($fareTiers) && $rawOffer) {
            try {
                $amadeus = app(AmadeusService::class);
                $response = $amadeus->upsellFlightOffers($rawOffer);

                Log::info("RAW_UPSELL_API_RESPONSE", [
                    "flight_id" => $id,
                    "response_keys" => array_keys($response),
                    "data_count" => isset($response["data"])
                        ? count($response["data"])
                        : 0,
                ]);

                if (isset($response["data"]) && !empty($response["data"])) {
                    $fareTiers = $this->parseUpsellOffers($response["data"]);
                    $this->fareDetails[$id] = $fareTiers;
                }
            } catch (\Exception $e) {
                Log::warning("UPSELL_FLIGHT_OFFERS_FAILED_NON_FATAL", [
                    "flight_id" => $id,
                    "error" => $e->getMessage(),
                ]);
                // Non-fatal: continue without upsell tiers
            }
        }

        Log::info("UPSOLD_FARE_TIERS_RESULT", [
            "flight_id" => $id,
            "count" => count($fareTiers),
            "tiers" => $fareTiers,
        ]);

        // ── Confirm Pricing (POST) for final services accuracy ──
        if ($rawOffer) {
            try {
                // Keep a reference to original amenities per segmentId
                $originalAmenities = [];
                $travelerPricings = $rawOffer["travelerPricings"] ?? [];
                if (!empty($travelerPricings)) {
                    foreach (
                        $travelerPricings[0]["fareDetailsBySegment"] ?? []
                        as $det
                    ) {
                        $originalAmenities[$det["segmentId"]] =
                            $det["amenities"] ?? [];
                    }
                }

                $amadeus = app(AmadeusService::class);
                $pricingResponse = $amadeus->priceFlightOffer([$rawOffer]);

                if (isset($pricingResponse["data"]["flightOffers"][0])) {
                    $pricedOffer = $pricingResponse["data"]["flightOffers"][0];

                    // Re-inject amenities if the pricing response stripped them out
                    if (
                        isset(
                            $pricedOffer["travelerPricings"][0][
                                "fareDetailsBySegment"
                            ],
                        )
                    ) {
                        foreach (
                            $pricedOffer["travelerPricings"][0][
                                "fareDetailsBySegment"
                            ]
                            as &$newDet
                        ) {
                            $sId = $newDet["segmentId"];
                            if (
                                empty($newDet["amenities"]) &&
                                !empty($originalAmenities[$sId])
                            ) {
                                $newDet["amenities"] = $originalAmenities[$sId];
                            }
                        }
                    }

                    // Update flight pricing and raw offer with the enriched priced one
                    $flight["price"] =
                        $pricedOffer["price"]["total"] ?? $flight["price"];
                    $flight["rawOffer"] = $pricedOffer;
                }
            } catch (\Exception $e) {
                Log::warning("PRICE_FLIGHT_OFFER_FAILED_NON_FATAL", [
                    "flight_id" => $id,
                    "error" => $e->getMessage(),
                ]);
                // Non-fatal: proceed to redirect with the original unpriced offer
            }
        }

        // Store selection and search context in session for the next step
        session([
            "selected_flight" => $flight,
            "selected_fare_tiers" => $fareTiers,
            "flight_search_params" => [
                "isMulti" => $this->isMulti,
                "segments" => $this->segments,
                "origin" => $this->origin,
                "destination" => $this->destination,
                "originIata" => $this->originIata,
                "destIata" => $this->destIata,
                "departDate" => $this->departDate,
                "returnDate" => $this->returnDate,
                "adultCount" => $this->adultCount,
                "childCount" => $this->childCount,
                "infantCount" => $this->infantCount,
                "travelClass" => $this->travelClass,
                "travelClassEnum" => $this->travelClassEnum,
                "currency" => $this->currencyCode,
            ],
        ]);
        Log::info("SELECT_FLIGHT_SUCCESS", ["flight_id" => $id]);
        return $this->redirect(route("additional.services"));
    }

    public function render()
    {
        return view("livewire.flightslist.flight-list");
    }
}
