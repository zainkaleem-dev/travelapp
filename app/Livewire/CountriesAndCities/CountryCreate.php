<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CountryCreate extends Component
{
    public string $country_code = '';
    public string $country_name = '';
    public string $country_dial_code = '';

    protected function rules(): array
    {
        return [
            'country_code' => ['required', 'string', 'size:2', 'unique:countries,code'],
            'country_name' => ['required', 'string', 'max:100'],
            'country_dial_code' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function save(): void
    {
        $this->country_code = strtoupper(trim($this->country_code));
        $this->validate();

        Country::create([
            'code' => $this->country_code,
            'name' => $this->country_name,
            'dial_code' => $this->country_dial_code,
        ]);

        session()->flash('status', 'Country created successfully.');
        $this->redirectRoute('admin.countries-and-cities');
    }

    public function render()
    {
        return view('livewire.countries-and-cities.country-create');
    }
}
