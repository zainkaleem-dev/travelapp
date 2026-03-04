<?php

namespace App\Livewire\Flightconfirmation;

use Livewire\Component;

class FlightConfirmation extends Component
{
    public string $bookingId;
    public string $bookingReference;

    public function mount()
    {
        $this->bookingId = session('amadeus_booking_id', '');
        $this->bookingReference = session('amadeus_booking_reference', '');

        if (!$this->bookingId) {
            $this->redirect(route('flights.search'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.flightconfirmation.flight-confirmation')
            ->layout('layouts.flight', ['title' => 'Booking Confirmed!']);
    }
}
