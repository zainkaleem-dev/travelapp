<?php

namespace App\Livewire\Pages\Booking;

use Livewire\Component;

class PaymentPage extends Component
{
    public function render()
    {
        return view('livewire.pages.booking.payment')
            ->layout('layouts.flightworld');
    }
}
