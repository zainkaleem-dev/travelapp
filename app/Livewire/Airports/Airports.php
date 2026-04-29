<?php

namespace App\Livewire\Airports;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class Airports extends Component
{
    public function render()
    {
        return view('livewire.airports.index');
    }
}
