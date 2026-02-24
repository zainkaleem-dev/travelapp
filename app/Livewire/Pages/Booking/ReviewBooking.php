<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class ReviewBooking extends Component
{
    public function render()
    {
        return view('livewire.pages.booking.review-booking')
            ->layout('layouts.flightworld');
    }
}
