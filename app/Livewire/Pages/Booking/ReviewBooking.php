<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class ReviewBooking extends Component
{
    public $pricedFlight = null;
    public $flightOffer = null;
    public $dictionaries = [];

    public function mount()
    {
        $this->pricedFlight = session('pricedFlight');

        if (!$this->pricedFlight || !isset($this->pricedFlight['data']['flightOffers'][0])) {
            // Fallback or redirect if no data
            return redirect()->route('home');
        }

        $this->flightOffer = $this->pricedFlight['data']['flightOffers'][0];
        $this->dictionaries = $this->pricedFlight['dictionaries'] ?? [];
    }

    public function render()
    {
        return view('livewire.pages.booking.review-booking')
            ->layout('layouts.flightworld');
    }
}
