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

    public function render()
    {
        return view('livewire.flightsearch.flight-search')->layout('layouts.flight');
    }

}
