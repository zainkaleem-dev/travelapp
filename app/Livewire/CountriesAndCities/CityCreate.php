<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\City;
use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CityCreate extends Component
{
    public ?int $country_id = null;
    public string $city_name = '';
    public string $city_code = '';

    protected function rules(): array
    {
        return [
            'country_id' => ['required', 'exists:countries,id'],
            'city_name' => ['required', 'string', 'max:100'],
            'city_code' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->city_code = strtoupper(trim($this->city_code));
        $this->validate();

        City::create([
            'country_id' => $this->country_id,
            'name' => $this->city_name,
            'code' => $this->city_code,
        ]);

        session()->flash('status', 'City created successfully.');
        $this->redirectRoute('admin.countries-and-cities');
    }

    public function render()
    {
        return view('livewire.countries-and-cities.city-create', [
            'countries' => Country::orderBy('name')->get(),
        ]);
    }
}
