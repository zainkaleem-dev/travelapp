<?php

namespace App\Livewire\Pages\Flights;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ListingReturn extends Component
{
    #[Reactive]
    public array $results = [];

    #[Reactive]
    public string $origin = '';

    #[Reactive]
    public string $destination = '';

    #[Reactive]
    public string $departureDate = '';

    #[Reactive]
    public string $returnDate = '';

    public function render()
    {
        return view('livewire.pages.flights.listing-return-livewire');
    }
}
