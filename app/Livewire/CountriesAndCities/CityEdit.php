<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\City;
use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CityEdit extends Component
{
    public City $city;
    public int $country_id;
    public string $name = '';
    public string $code = '';

    public function mount(City $city): void
    {
        $this->city = $city;
        $this->country_id = $city->country_id;
        $this->name = $city->name;
        $this->code = $city->code ?? '';
    }

    protected function rules(): array
    {
        return [
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->city->update([
            'country_id' => $this->country_id,
            'name' => $this->name,
            'code' => $this->code,
        ]);

        session()->flash('status', 'City updated successfully.');
        $this->redirectRoute('admin.countries-and-cities');
    }

    public function render()
    {
        return view('livewire.countries-and-cities.city-edit', [
            'countries' => Country::all(),
        ]);
    }
}
