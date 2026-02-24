<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class TravellerDetails extends Component
{
    public function render()
    {
        return view('livewire.pages.booking.traveller-details')
            ->layout('layouts.flightworld');
    }
}
