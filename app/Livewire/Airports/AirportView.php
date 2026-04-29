<?php

namespace App\Livewire\Airports;

use App\Models\Airport;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class AirportView extends Component
{
    public Airport $airport;

    public function mount(Airport $airport): void
    {
        $this->airport = $airport;
    }

    public function render()
    {
        return view('livewire.airports.view');
    }
}
