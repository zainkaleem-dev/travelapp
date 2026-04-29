<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\City;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CityView extends Component
{
    public City $city;

    public function mount(City $city): void
    {
        $this->city = $city;
    }

    public function render()
    {
        return view('livewire.countries-and-cities.city-view');
    }
}
