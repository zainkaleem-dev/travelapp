<?php

namespace App\Livewire\Pages\Flights;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ListingOneway extends Component
{
    #[Reactive]
    public $oneWayOrigin = '';

    #[Reactive]
    public $oneWayDestination = '';

    #[Reactive]
    public $oneWayDepartureDate = '';

    #[Reactive]
    public $flightResults = null;

    public function render()
    {
        return view('livewire.pages.flights.listing-oneway');
    }
}
