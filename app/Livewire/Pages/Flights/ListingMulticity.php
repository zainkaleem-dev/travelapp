<?php

namespace App\Livewire\Pages\Flights;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ListingMulticity extends Component
{
    #[Reactive]
    public array $results = [];

    #[Reactive]
    public array $segments = [];

    public function render()
    {
        return view('livewire.pages.flights.listing-multicity-livewire');
    }
}
