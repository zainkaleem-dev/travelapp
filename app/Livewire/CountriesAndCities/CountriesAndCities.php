<?php

namespace App\Livewire\CountriesAndCities;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class CountriesAndCities extends Component
{
    public function render()
    {
        return view('livewire.countries-and-cities.index');
    }
}
