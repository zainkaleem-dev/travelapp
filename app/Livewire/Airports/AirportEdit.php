<?php

namespace App\Livewire\Airports;

use App\Models\Airport;
use App\Models\City;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class AirportEdit extends Component
{
    public Airport $airport;
    public int $city_id;
    public string $name = '';
    public string $code = '';
    public string $icao_code = '';

    public function mount(Airport $airport): void
    {
        $this->airport = $airport;
        $this->city_id = $airport->city_id;
        $this->name = $airport->name;
        $this->code = $airport->code;
        $this->icao_code = $airport->icao_code ?? '';
    }

    protected function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'size:3'],
            'icao_code' => ['nullable', 'string', 'size:4'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->airport->update([
            'city_id' => $this->city_id,
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'icao_code' => strtoupper($this->icao_code),
        ]);

        session()->flash('status', 'Airport updated successfully.');
        $this->redirectRoute('admin.airports');
    }

    public function render()
    {
        return view('livewire.airports.edit', [
            'cities' => City::orderBy('name')->get(),
        ]);
    }
}
