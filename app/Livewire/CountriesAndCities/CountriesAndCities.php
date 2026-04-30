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
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

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
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
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
            'countries' => Country::orderBy($this->sortBy, $this->sortDirection)->get(),
            'cities' => City::with('country')->orderBy($this->sortBy, $this->sortDirection)->get(),
        ]);
    }
}
