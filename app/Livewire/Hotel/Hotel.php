<?php

namespace App\Livewire\Hotel;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Hotel extends Component
{
    public function render()
    {
        return view('livewire.hotel.hotel');
    }
}

