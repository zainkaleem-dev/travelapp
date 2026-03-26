<?php

namespace App\Livewire\Flightsearch;

use App\Models\UserSetting;
use App\Models\User;
use App\Services\AmadeusService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.flight')]
class FlightSearch extends Component
{
    protected array $messages = [
        // Return
        'returnDep.required' => 'Departure airport is required.',
        'returnArr.required' => 'Arrival airport is required.',
        'returnDepDate.required' => 'Departure date is required.',
        'returnDepDate.date' => 'Departure date must be a valid date.',
        'returnRetDate.required' => 'Return date is required.',
        'returnRetDate.date' => 'Return date must be a valid date.',
        'returnRetDate.after' => 'Return date must be after the departure date.',

        // One-way
        'onewayDep.required' => 'Departure airport is required.',
        'onewayArr.required' => 'Arrival airport is required.',
        'onewayDepDate.required' => 'Departure date is required.',
        'onewayDepDate.date' => 'Departure date must be a valid date.',

        // Multi-city
        'multiFlights.*.dep.required' => 'Departure airport is required.',
        'multiFlights.*.arr.required' => 'Arrival airport is required.',
        'multiFlights.*.date.required' => 'Departure date is required.',
        'multiFlights.*.date.date' => 'Departure date must be a valid date.',
    ];

    // Search results from Amadeus
    public array $airportSearchResults = [];
    public string $searchType = ''; // 'returnDep', 'returnArr', 'onewayDep', 'onewayArr', 'multi.0.dep', etc.

    /** From `settings.trip_type` for logged-in user (display only) */
    public ?string $savedTripPurposeLabel = null;

    // ── Trip type tab ──────────────────────────────────────────────────
    public string $tripType = 'return'; // return | oneway | multi

    // ── Return fields ──────────────────────────────────────────────────
    public string $returnDep = '';
    public string $returnArr = '';
    public string $returnUserSearch = ''; // "Users" dropdown search (Return trip)
    public ?int $returnSelectedUserId = null;
    public array $returnUserSearchResults = [];
    public string $returnDepDate = '';
    public string $returnRetDate = '';
    public string $returnPax = '1 Adult';
    public string $returnClass = 'Economy Class';
    public bool $returnFlexible = false;
    public int $returnAdults = 1;
    public int $returnChildren = 0;
    public int $returnInfants = 0;

    // ── One-way fields ─────────────────────────────────────────────────
    public string $onewayDep = '';
    public string $onewayArr = '';
    public string $onewayUserSearch = ''; // "Users" dropdown search (One-way trip)
    public ?int $onewaySelectedUserId = null;
    public array $onewayUserSearchResults = [];
    public string $onewayDepDate = '';
    public string $onewayPax = '1 Adult';
    public string $onewayClass = 'Economy Class';
    public bool $onewayFlexible = false;
    public int $onewayAdults = 1;
    public int $onewayChildren = 0;
    public int $onewayInfants = 0;

    // ── Multi-city fields ──────────────────────────────────────────────
    public array $multiFlights = [
        ['dep' => '', 'arr' => '', 'date' => ''],
        ['dep' => '', 'arr' => '', 'date' => ''],
    ];
    public string $multiUserSearch = ''; // "Users" dropdown search (Multi-city trip)
    public ?int $multiSelectedUserId = null;
    public array $multiUserSearchResults = [];
    public string $multiPax = '1 Adult';
    public string $multiClass = 'Economy Class';
    public bool $multiFlexible = false;
    public int $multiAdults = 1;
    public int $multiChildren = 0;
    public int $multiInfants = 0;

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

    public function mount(): void
    {
        $this->loadSavedTripPurposeFromSettings();
    }

    #[On('user-settings-updated')]
    public function loadSavedTripPurposeFromSettings(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->savedTripPurposeLabel = null;

            return;
        }

        $key = UserSetting::query()->where('user_id', $user->id)->value('trip_type');
        $this->savedTripPurposeLabel = UserSetting::tripTypeLabel($key);
    }

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

        try {
            $this->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->searching = false;
            throw $e;
        }

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

            $this->redirectRoute('flights.list', navigate: true);
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

            $this->redirectRoute('flights.list', navigate: true);
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

            $this->redirectRoute('flights.list', navigate: true);
            return;
        }

        // Fallback or unexpected state
        return;
    }

    public function doneSearching(): void
    {
        $this->searching = false;
    }

    public function updatedReturnUserSearch(): void
    {
        $q = trim($this->returnUserSearch);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->returnUserSearchResults = [];
            return;
        }

        $this->fetchUsers($q);
    }

    /**
     * Search users by name/email for the Return "Users" dropdown.
     */
    public function fetchUsers(string $query): void
    {
        $q = trim($query);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->returnUserSearchResults = [];
            return;
        }

        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();

        $this->returnUserSearchResults = $users->map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name ?? '',
                'email' => $u->email ?? '',
            ];
        })->all();
    }

    public function selectReturnUser(int $userId): void
    {
        $user = User::query()->find($userId);
        if (!$user) {
            return;
        }

        $this->returnSelectedUserId = $user->id;
        $this->returnUserSearch = $user->name ?? '';
        $this->returnUserSearchResults = [];
    }

    public function clearReturnUser(): void
    {
        $this->returnSelectedUserId = null;
        $this->returnUserSearch = '';
        $this->returnUserSearchResults = [];
    }

    public function updatedOnewayUserSearch(): void
    {
        $q = trim($this->onewayUserSearch);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->onewayUserSearchResults = [];
            return;
        }

        $this->fetchOnewayUsers($q);
    }

    /**
     * Search users by name/email for the One-way "Users" dropdown.
     */
    public function fetchOnewayUsers(string $query): void
    {
        $q = trim($query);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->onewayUserSearchResults = [];
            return;
        }

        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();

        $this->onewayUserSearchResults = $users->map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name ?? '',
                'email' => $u->email ?? '',
            ];
        })->all();
    }

    public function selectOnewayUser(int $userId): void
    {
        $user = User::query()->find($userId);
        if (!$user) {
            return;
        }

        $this->onewaySelectedUserId = $user->id;
        $this->onewayUserSearch = $user->name ?? '';
        $this->onewayUserSearchResults = [];
    }

    public function clearOnewayUser(): void
    {
        $this->onewaySelectedUserId = null;
        $this->onewayUserSearch = '';
        $this->onewayUserSearchResults = [];
    }

    public function updatedMultiUserSearch(): void
    {
        $q = trim($this->multiUserSearch);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->multiUserSearchResults = [];
            return;
        }

        $this->fetchMultiUsers($q);
    }

    /**
     * Search users by name/email for the Multi-city "Users" dropdown.
     */
    public function fetchMultiUsers(string $query): void
    {
        $q = trim($query);

        if ($q === '' || mb_strlen($q) < 2) {
            $this->multiUserSearchResults = [];
            return;
        }

        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();

        $this->multiUserSearchResults = $users->map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name ?? '',
                'email' => $u->email ?? '',
            ];
        })->all();
    }

    public function selectMultiUser(int $userId): void
    {
        $user = User::query()->find($userId);
        if (!$user) {
            return;
        }

        $this->multiSelectedUserId = $user->id;
        $this->multiUserSearch = $user->name ?? '';
        $this->multiUserSearchResults = [];
    }

    public function clearMultiUser(): void
    {
        $this->multiSelectedUserId = null;
        $this->multiUserSearch = '';
        $this->multiUserSearchResults = [];
    }

    private function getLocationsCacheFilePath(): string
    {
        return storage_path('app/flightsearch/locations.json');
    }

    /**
     * @return array{generated_at:?string,countries:array<int, array{name:string,code:string}>,airports:array<int, array{code:string,city:string,country:string,airport:string}>}
     */
    private function loadLocationsCachePayload(): array
    {
        $path = $this->getLocationsCacheFilePath();
        if (!is_file($path)) {
            return [
                'generated_at' => null,
                'countries' => [],
                'airports' => [],
            ];
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return [
                'generated_at' => null,
                'countries' => [],
                'airports' => [],
            ];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [
                'generated_at' => null,
                'countries' => [],
                'airports' => [],
            ];
        }

        return [
            'generated_at' => $decoded['generated_at'] ?? null,
            'countries' => is_array($decoded['countries'] ?? null) ? $decoded['countries'] : [],
            'airports' => is_array($decoded['airports'] ?? null) ? $decoded['airports'] : [],
        ];
    }

    /**
     * @param array{generated_at:?string,countries:array<int, array{name:string,code:string}>,airports:array<int, array{code:string,city:string,country:string,airport:string}>} $payload
     */
    private function writeLocationsCachePayload(array $payload): void
    {
        $path = $this->getLocationsCacheFilePath();
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $payload['generated_at'] = now()->toISOString();

        $tmpPath = $path . '.tmp';
        file_put_contents($tmpPath, json_encode($payload, JSON_UNESCAPED_UNICODE));
        rename($tmpPath, $path);
    }

    /**
     * @param array<string, mixed> $location
     * @return array{airport:array{code:string,city:string,country:string,airport:string,display:string},country:?array{name:string,code:string}}
     */
    private function mapApiLocationToCacheEntry(array $location): array
    {
        $address = $location['address'] ?? [];
        $cityName = $address['cityName'] ?? '';
        $countryName = $address['countryName'] ?? '';
        $countryCode = $address['countryCode'] ?? '';
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
            'airport' => [
                'code' => $iataCode,
                'city' => $cityName,
                'country' => $countryName,
                'airport' => $airportName,
                'display' => $display,
            ],
            'country' => ($countryCode !== '' && $countryName !== '')
                ? [
                    'name' => $countryName,
                    'code' => $countryCode,
                ]
                : null,
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $locations
     * @return array<int, array{code:string,city:string,country:string,airport:string,display:string}>
     */
    private function mergeApiLocationsIntoCache(array $locations): array
    {
        $payload = $this->loadLocationsCachePayload();

        $airportsByCode = [];
        foreach ($payload['airports'] as $airport) {
            $code = $airport['code'] ?? '';
            if ($code === '') {
                continue;
            }

            $airportsByCode[$code] = [
                'code' => $code,
                'city' => $airport['city'] ?? '',
                'country' => $airport['country'] ?? '',
                'airport' => $airport['airport'] ?? '',
            ];
        }

        $countriesByCode = [];
        foreach ($payload['countries'] as $country) {
            $code = $country['code'] ?? '';
            $name = $country['name'] ?? '';
            if ($code === '' || $name === '') {
                continue;
            }

            $countriesByCode[$code] = [
                'code' => $code,
                'name' => $name,
            ];
        }

        $results = [];
        foreach ($locations as $location) {
            $mapped = $this->mapApiLocationToCacheEntry($location);
            $airport = $mapped['airport'];

            if (($airport['code'] ?? '') !== '') {
                $airportsByCode[$airport['code']] = [
                    'code' => $airport['code'],
                    'city' => $airport['city'],
                    'country' => $airport['country'],
                    'airport' => $airport['airport'],
                ];
                $results[] = $airport;
            }

            if ($mapped['country'] !== null) {
                $countriesByCode[$mapped['country']['code']] = $mapped['country'];
            }
        }

        ksort($countriesByCode);
        ksort($airportsByCode);

        $this->writeLocationsCachePayload([
            'generated_at' => $payload['generated_at'],
            'countries' => array_values($countriesByCode),
            'airports' => array_values($airportsByCode),
        ]);

        return array_slice($results, 0, 8);
    }

    /**
     * Filters cached airports by substring match across city/country/airport/code.
     *
     * @param array<int, array{code:string,city:string,country:string,airport:string}> $allAirports
     * @return array<int, array{code:string,city:string,country:string,airport:string}>
     */
    private function filterCachedAirports(array $allAirports, string $query): array
    {
        $q = mb_strtolower($query);

        $matches = [];
        foreach ($allAirports as $a) {
            $city = mb_strtolower($a['city'] ?? '');
            $country = mb_strtolower($a['country'] ?? '');
            $airport = mb_strtolower($a['airport'] ?? '');
            $code = mb_strtolower($a['code'] ?? '');

            if (
                $q === '' ||
                (str_contains($city, $q) || str_contains($country, $q) || str_contains($airport, $q) || str_contains($code, $q))
            ) {
                $matches[] = [
                    'code' => $a['code'] ?? '',
                    'city' => $a['city'] ?? '',
                    'country' => $a['country'] ?? '',
                    'airport' => $a['airport'] ?? '',
                ];
                if (count($matches) >= 8) {
                    break;
                }
            }
        }

        return $matches;
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

        // Primary source is the local JSON cache built by the job.
        // Only hit Amadeus when a location is missing, then merge that result
        // back into the JSON so future searches stay local.
        $payload = $this->loadLocationsCachePayload();
        $cachedAirports = $payload['airports'];
        if (!empty($cachedAirports)) {
            $cachedResults = $this->filterCachedAirports($cachedAirports, $q);
            if (!empty($cachedResults)) {
                $this->airportSearchResults = $cachedResults;
                return;
            }
        }

        try {
            $service = app(AmadeusService::class);
            $response = $service->searchLocations($q);

            if (isset($response['data']) && is_array($response['data'])) {
                $results = $this->mergeApiLocationsIntoCache($response['data']);

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
        $this->fetchAirports($this->returnDep, 'returnDep');
    }

    public function updatedReturnDepDate(): void
    {
        $this->resetValidation(['returnDepDate', 'returnRetDate']);

        if (
            $this->returnDepDate !== '' &&
            $this->returnRetDate !== '' &&
            $this->returnRetDate <= $this->returnDepDate
        ) {
            $this->returnRetDate = '';
        }
    }

    public function updatedReturnRetDate(): void
    {
        $this->resetValidation('returnRetDate');
    }

    public function updatedReturnArr(): void
    {
        if (str_contains($this->returnArr, ' (')) {
            return;
        }
        $this->fetchAirports($this->returnArr, 'returnArr');
    }

    public function selectReturnDepAirport(string $display): void
    {
        \Log::info("selectReturnDepAirport called with: " . $display);
        $this->returnDep = $display;
    }

    public function selectReturnArrAirport(string $display): void
    {
        \Log::info("selectReturnArrAirport called with: " . $display);
        $this->returnArr = $display;
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
    }

    public function selectOnewayArrAirport(string $display): void
    {
        $this->onewayArr = $display;
    }

    public function selectMultiDepAirport(int $index, string $display): void
    {
        \Log::info("selectMultiDepAirport called for index $index with: " . $display);
        if (!isset($this->multiFlights[$index])) {
            return;
        }
        $this->multiFlights[$index]['dep'] = $display;
    }

    public function selectMultiArrAirport(int $index, string $display): void
    {
        \Log::info("selectMultiArrAirport called for index $index with: " . $display);
        if (!isset($this->multiFlights[$index])) {
            return;
        }
        $this->multiFlights[$index]['arr'] = $display;
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
                        $this->fetchAirports($value, "multi.$index.dep");
                    } else if ($field === 'arr') {
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
        return view('livewire.flightsearch.flight-search', ['quick' => false]);
    }

}
