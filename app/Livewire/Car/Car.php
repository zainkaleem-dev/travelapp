<?php

namespace App\Livewire\Car;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Car extends Component
{
    public function render()
    {
        return view('livewire.car.car');
    }
}

