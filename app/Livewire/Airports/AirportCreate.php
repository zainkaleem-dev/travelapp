<?php

namespace App\Livewire\Airports;

use App\Models\Airport;
use App\Models\City;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class AirportCreate extends Component
{
    public ?int $city_id = null;
    public string $name = '';
    public string $code = '';
    public string $icao_code = '';

    protected function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:airports,code'],
            'icao_code' => ['nullable', 'string', 'max:255', 'unique:airports,icao_code'],
        ];
    }

    public function save(): void
    {
        $this->code = strtoupper(trim($this->code));
        $this->icao_code = strtoupper(trim($this->icao_code));
        $this->validate();

        Airport::create([
            'city_id' => $this->city_id,
            'name' => $this->name,
            'code' => $this->code,
            'icao_code' => $this->icao_code ?: null,
        ]);

        session()->flash('status', 'Airport created successfully.');
        $this->redirectRoute('admin.airports');
    }

    public function render()
    {
        return view('livewire.airports.create', [
            'cities' => City::with('country')->orderBy('name')->get(),
        ]);
    }
}
