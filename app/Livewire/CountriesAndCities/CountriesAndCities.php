<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\Country;
use App\Models\City;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class CountriesAndCities extends Component
{
    public string $activeTab = 'countries'; // 'countries' or 'cities'
    public ?string $crudMessage = null;

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->crudMessage = session('status');
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->crudMessage = null;
    }

    public function deleteCountry(int $id): void
    {
        Country::findOrFail($id)->delete();
        $this->crudMessage = 'Country deleted.';
    }

    public function deleteCity(int $id): void
    {
        City::findOrFail($id)->delete();
        $this->crudMessage = 'City deleted.';
    }

    public function render()
    {
        return view('livewire.countries-and-cities.index', [
            'countries' => Country::orderBy('name')->get(),
            'cities' => City::with('country')->orderBy('name')->get(),
        ]);
    }
}
