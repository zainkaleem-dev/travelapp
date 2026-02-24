<?php

namespace App\Livewire\Pages\Flights;

use Livewire\Component;

class ListingOneway extends Component
{
    public function render()
    {
        return view('livewire.pages.flights.listing-oneway')
            ->layout('layouts.flightworld');
    }
}
