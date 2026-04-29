<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CountryEdit extends Component
{
    public Country $country;
    public string $name = '';
    public string $code = '';
    public string $dial_code = '';

    public function mount(Country $country): void
    {
        $this->country = $country;
        $this->name = $country->name;
        $this->code = $country->code ?? '';
        $this->dial_code = $country->dial_code ?? '';
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10'],
            'dial_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->country->update([
            'name' => $this->name,
            'code' => $this->code,
            'dial_code' => $this->dial_code,
        ]);

        session()->flash('status', 'Country updated successfully.');
        $this->redirectRoute('admin.countries-and-cities');
    }

    public function render()
    {
        return view('livewire.countries-and-cities.country-edit');
    }
}
