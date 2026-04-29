<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CountryView extends Component
{
    public Country $country;

    public function mount(Country $country): void
    {
        $this->country = $country;
    }

    public function render()
    {
        return view('livewire.countries-and-cities.country-view');
    }
}
