<?php

namespace App\Livewire\Airports;

use App\Models\Airport;
use App\Models\City;
use App\Services\PaginationService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class Airports extends Component
{
    public ?string $crudMessage = null;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    // Pagination & Filter properties
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
    public string $cityFilter = '';

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->crudMessage = session('status');
        }
        $this->currentPage = (int) request()->query('page', 1);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedPerPage(): void
    {
        $this->currentPage = 1;
    }

    public function updatedCityFilter(): void
    {
        $this->currentPage = 1;
    }

    #[\Livewire\Attributes\On('paginationGoTo')]
    public function goToPage($page): void
    {
        $this->currentPage = (int) $page;
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->currentPage = 1;
    }

    public function deleteAirport(int $id): void
    {
        Airport::findOrFail($id)->delete();
        $this->crudMessage = 'Airport deleted.';
    }

    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);

        $query = Airport::with('city.country')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('iata_code', 'like', "%{$search}%")
                        ->orWhereHas('city', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->cityFilter, function ($query) {
                $query->where('city_id', $this->cityFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $airports = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.airports.index', [
            'airports' => $airports,
            'cities' => City::orderBy('name')->get(),
            'paginationMeta' => $paginationService->getPaginationMeta($airports),
        ]);
    }
}
