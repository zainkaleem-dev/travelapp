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
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->crudMessage = session('status');
        }
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

    public function deleteAirport(int $id): void
    {
        Airport::findOrFail($id)->delete();
        $this->crudMessage = 'Airport deleted.';
    }

    public function render()
    {
        return view('livewire.airports.index', [
            'airports' => Airport::with('city.country')->orderBy($this->sortBy, $this->sortDirection)->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }
}
