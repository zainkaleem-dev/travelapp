<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class BookingConfirmationSuccess extends Component
{
    public function render()
    {
        return view('livewire.pages.booking.booking-confirmation-success')
            ->layout('layouts.flightworld');
    }
}
