<?php

namespace App\Livewire\Travelhub;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TravelHub extends Component
{
    public function render()
    {
        return view('Livewire.travelhub.travel-hub');
    }
}

