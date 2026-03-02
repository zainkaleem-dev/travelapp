<?php

namespace App\Livewire\Flightslist;

use Livewire\Component;
use Livewire\Attributes\Computed;

class FlightList extends Component
{
    // ─── Search Form ──────────────────────────────────────────────
    public string $origin = 'Düsseldorf Airport (DUS)';
    public string $destination = 'Istanbul Airport (IST)';
    public string $departDate = '2026-07-17';
    public string $returnDate = '2026-07-28';
    public int $passengers = 1;

    // Autocomplete dropdown toggles
    public bool $showOriginAirports = false;
    public bool $showDestinationAirports = false;

    // Passenger breakdown for UI (kept in sync with $passengers)
    public int $adultCount = 1;
    public int $childCount = 0;
    public int $infantCount = 0;

    // ─── Filters ──────────────────────────────────────────────────
    public int $priceMin = 100;
    public int $priceMax = 1000;
    public array $stops = ['any'];
    public array $airlines = ['any'];
    public array $departTimes = ['any'];

    // ─── Sorting / Date ──────────────────────────────────────────
    public string $sortTab = 'cheap';
    public string $selectedDate = '2026-07-14';

    // ─── Date rail (7 days around selected date) ──────────────────
    public array $dateRail = [
        ['date' => '2026-07-11', 'label' => 'Tue 11.07', 'price' => 175],
        ['date' => '2026-07-12', 'label' => 'Wed 12.07', 'price' => 164],
        ['date' => '2026-07-13', 'label' => 'Thu 13.07', 'price' => 196],
        ['date' => '2026-07-14', 'label' => 'Sat 14.07', 'price' => 322],
        ['date' => '2026-07-15', 'label' => 'Sun 15.07', 'price' => 364],
        ['date' => '2026-07-16', 'label' => 'Mon 16.07', 'price' => 178],
        ['date' => '2026-07-17', 'label' => 'Tue 17.07', 'price' => 180],
    ];

    // ─── Raw flight data ──────────────────────────────────────────
    protected array $allFlights = [
        [
            'id' => 1,
            'airline' => 'TK',
            'airlineName' => 'Turkish Airlines',
            'airlineColor' => 'bg-red-500',
            'dep' => '17:30',
            'arr' => '23:18',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 15m',
            'stops' => 'Direct',
            'price' => 330,
            'oldPrice' => 487,
            'badge' => 'Best Choice',
            'badgeClass' => 'best-badge',
            'btnClass' => 'bg-orange-500 hover:bg-orange-600',
            'bgClass' => 'bg-orange-50/50',
        ],
        [
            'id' => 2,
            'airline' => 'PC',
            'airlineName' => 'Pegasus Airlines',
            'airlineColor' => 'bg-orange-500',
            'dep' => '01:40',
            'arr' => '13:30',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf Int. Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 10m',
            'stops' => 'Direct',
            'price' => 298,
            'oldPrice' => null,
            'badge' => 'Cheapest',
            'badgeClass' => 'cheapest-badge',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => 'bg-green-50/30',
            'note' => 'Last 2 Seats',
        ],
        [
            'id' => 3,
            'airline' => 'TK',
            'airlineName' => 'Turkish Airlines',
            'airlineColor' => 'bg-red-500',
            'dep' => '12:30',
            'arr' => '23:58',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 28m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => null,
            'badgeClass' => '',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
        [
            'id' => 4,
            'airline' => 'TK',
            'airlineName' => 'Turkish Airlines',
            'airlineColor' => 'bg-red-500',
            'dep' => '17:30',
            'arr' => '03:18',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 48m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => 'Fastest Route',
            'badgeClass' => 'bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded mb-1 inline-block',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
        [
            'id' => 5,
            'airline' => 'PC',
            'airlineName' => 'Pegasus Airlines',
            'airlineColor' => 'bg-orange-500',
            'dep' => '17:30',
            'arr' => '03:18',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 48m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => 'Approximate Price',
            'badgeClass' => 'bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded mb-1 inline-block',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
            'note' => 'Last 2 Seats',
        ],
        [
            'id' => 6,
            'airline' => 'TK',
            'airlineName' => 'Turkish Airlines',
            'airlineColor' => 'bg-red-500',
            'dep' => '10:30',
            'arr' => '21:48',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 18m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => null,
            'badgeClass' => '',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
        [
            'id' => 7,
            'airline' => 'LH',
            'airlineName' => 'Lufthansa',
            'airlineColor' => 'bg-yellow-500',
            'dep' => '17:30',
            'arr' => '22:15',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '3h 45m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => null,
            'badgeClass' => '',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
        [
            'id' => 8,
            'airline' => 'PC',
            'airlineName' => 'Pegasus Airlines',
            'airlineColor' => 'bg-orange-500',
            'dep' => '08:30',
            'arr' => '18:58',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 28m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => null,
            'badgeClass' => '',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
        [
            'id' => 9,
            'airline' => 'TK',
            'airlineName' => 'Turkish Airlines',
            'airlineColor' => 'bg-red-500',
            'dep' => '14:30',
            'arr' => '14:58',
            'depCity' => 'Düsseldorf',
            'depAirport' => 'Düsseldorf International Airport (DUS)',
            'arrCity' => 'Istanbul',
            'arrAirport' => 'Istanbul Airport (IST)',
            'duration' => '4h 28m',
            'stops' => 'Direct',
            'price' => 370,
            'oldPrice' => null,
            'badge' => null,
            'badgeClass' => '',
            'btnClass' => 'bg-blue-600 hover:bg-blue-700',
            'bgClass' => '',
        ],
    ];

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
            return true;
        });

        // Sort
        match ($this->sortTab) {
            'cheap' => usort($results, fn($a, $b) => $a['price'] <=> $b['price']),
            'fastest' => usort($results, fn($a, $b) => $a['duration'] <=> $b['duration']),
            default => null,
        };

        return array_values($results);
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
    }

    public function setSort(string $tab): void
    {
        $this->sortTab = $tab;
    }

    public function clearFilters(): void
    {
        $this->priceMin = 100;
        $this->priceMax = 1000;
        $this->stops = ['any'];
        $this->airlines = ['any'];
        $this->departTimes = ['any'];
    }

    // ─── Airport helpers (copied from FlightSearch) ───────────────
    public function getAirportsProperty(): array
    {
        return [
            ['city' => 'Islamabad', 'country' => 'Pakistan', 'airport' => 'Islamabad International Airport', 'code' => 'ISB'],
            ['city' => 'Lahore', 'country' => 'Pakistan', 'airport' => 'Allama Iqbal International Airport', 'code' => 'LHE'],
            ['city' => 'Karachi', 'country' => 'Pakistan', 'airport' => 'Jinnah International Airport', 'code' => 'KHI'],
            ['city' => 'Dubai', 'country' => 'United Arab Emirates', 'airport' => 'Dubai International Airport', 'code' => 'DXB'],
            ['city' => 'Abu Dhabi', 'country' => 'United Arab Emirates', 'airport' => 'Zayed International Airport', 'code' => 'AUH'],
            ['city' => 'Doha', 'country' => 'Qatar', 'airport' => 'Hamad International Airport', 'code' => 'DOH'],
            ['city' => 'Istanbul', 'country' => 'Türkiye', 'airport' => 'Istanbul Airport', 'code' => 'IST'],
            ['city' => 'London', 'country' => 'United Kingdom', 'airport' => 'Heathrow Airport', 'code' => 'LHR'],
            ['city' => 'New York', 'country' => 'United States', 'airport' => 'John F. Kennedy International Airport', 'code' => 'JFK'],
        ];
    }

    public function filteredAirports(string $query): array
    {
        $q = trim(mb_strtolower($query));
        if ($q === '') {
            return array_slice($this->airports, 0, 8);
        }

        $matches = array_values(array_filter($this->airports, function (array $a) use ($q) {
            $hay = mb_strtolower($a['city'] . ' ' . $a['country'] . ' ' . $a['airport'] . ' ' . $a['code']);
            return str_contains($hay, $q);
        }));

        return array_slice($matches, 0, 8);
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
            $this->childCount--;
        } elseif ($type === 'infant') {
            if ($this->infantCount <= 0) {
                return;
            }
            $this->infantCount--;
        }

        $this->syncPassengersTotal();
    }

    public function selectFlight(int $id): void
    {
        // In a real app: redirect to passenger details step
        session()->flash('selected', $id);
        $this->dispatch('flight-selected', id: $id);
    }

    public function render()
    {
        return view('livewire.flightslist.flight-list')
            ->layout('layouts.flight');
    }

}
