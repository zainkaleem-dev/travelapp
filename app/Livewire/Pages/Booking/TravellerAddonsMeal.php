<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class TravellerAddonsMeal extends Component
{
    public function render()
    {
        return view('livewire.pages.booking.traveller-addons-meal')
            ->layout('layouts.flightworld');
    }
}
