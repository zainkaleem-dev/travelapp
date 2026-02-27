<?php

namespace App\Livewire\Flightsearch;

use Livewire\Component;

class FlightSearch extends Component
{
    // ── Trip type tab ──────────────────────────────────────────────────
    public string $tripType = 'return'; // return | oneway | multi

    // ── Return fields ──────────────────────────────────────────────────
    public string $returnDep = 'Islamabad (ISB)';
    public string $returnArr = '';
    public string $returnDepDate = '';
    public string $returnRetDate = '';
    public string $returnPax = '1 Adult';
    public string $returnClass = 'Economy Class';
    public bool $returnPromo = false;
    public string $returnPromoCode = '';
    public bool $returnFlexible = false;
    public int $returnAdults = 1;
    public int $returnChildren = 0;
    public int $returnInfants = 0;

    // Airport autocomplete state (Return tab)
    public bool $showReturnDepAirports = false;
    public bool $showReturnArrAirports = false;

    // ── One-way fields ─────────────────────────────────────────────────
    public string $onewayDep = 'Islamabad (ISB)';
    public string $onewayArr = '';
    public string $onewayDepDate = '';
    public string $onewayPax = '1 Adult';
    public string $onewayClass = 'Economy Class';
    public bool $onewayPromo = false;
    public string $onewayPromoCode = '';

    // ── Multi-city fields ──────────────────────────────────────────────
    public array $multiFlights = [
        ['dep' => 'Islamabad (ISB)', 'arr' => '', 'date' => ''],
        ['dep' => '', 'arr' => '', 'date' => ''],
    ];
    public string $multiPax = '1 Adult';
    public string $multiClass = 'Economy Class';
    public bool $multiPromo = false;
    public string $multiPromoCode = '';

    // ── Searching state ────────────────────────────────────────────────
    public bool $searching = false;

    // ── Constants ─────────────────────────────────────────────────────
    const MAX_FLIGHTS = 5;

    // ── Computed helpers ───────────────────────────────────────────────
    #[Computed]
    public function canAddFlight(): bool
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
        $this->dispatch('search-started');

        // Reset searching after 2 seconds via JS
        $this->js("setTimeout(() => \$wire.doneSearching(), 2000)");
    }

    public function doneSearching(): void
    {
        $this->searching = false;
    }

    public function getAirportsProperty(): array
    {
        // Minimal static dataset for UI; replace with DB/API later.
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

    public function updatedReturnDep(): void
    {
        $this->showReturnDepAirports = true;
    }

    public function updatedReturnArr(): void
    {
        $this->showReturnArrAirports = true;
    }

    public function selectReturnDepAirport(string $display): void
    {
        $this->returnDep = $display;
        $this->showReturnDepAirports = false;
    }

    public function selectReturnArrAirport(string $display): void
    {
        $this->returnArr = $display;
        $this->showReturnArrAirports = false;
    }

    public function closeAirportDropdowns(): void
    {
        $this->showReturnDepAirports = false;
        $this->showReturnArrAirports = false;
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

    public function render()
    {
        return view('livewire.flightsearch.flight-search')->layout('layouts.flight');
    }

}
