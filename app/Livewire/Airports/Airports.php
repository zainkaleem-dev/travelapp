<?php

namespace App\Livewire\Airports;

use App\Models\Airport;
use App\Models\City;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class Airports extends Component
{
    public ?string $crudMessage = null;

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->crudMessage = session('status');
        }
    }

    public function deleteAirport(int $id): void
    {
        Airport::findOrFail($id)->delete();
        $this->crudMessage = 'Airport deleted.';
    }

    public function render()
    {
        return view('livewire.airports.index', [
            'airports' => Airport::with('city.country')->orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }
}
