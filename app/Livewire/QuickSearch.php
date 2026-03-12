<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AmadeusService;

class QuickSearch extends \App\Livewire\Flightsearch\FlightSearch
{
    public function render()
    {
        // Reuse the main flight-search view but in compact mode (no hero).
        return view('livewire.flightsearch.flight-search', ['quick' => true]);
    }
}

