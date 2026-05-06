<?php

namespace App\Livewire\CountriesAndCities;

use App\Models\Country;
use App\Models\City;
use App\Services\PaginationService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class CountriesAndCities extends Component
{
    public string $activeTab = 'countries'; // 'countries' or 'cities'
    public ?string $crudMessage = null;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    
    // Pagination & Filter properties
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;

    public function mount(): void
    {
        if (session()->has('status')) {
            $this->crudMessage = session('status');
        }
        $this->currentPage = (int) request()->query('page', 1);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->crudMessage = null;
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->currentPage = 1;
        $this->search = '';
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedPerPage(): void
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

    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);

        if ($this->activeTab === 'countries') {
            $query = Country::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('dial_code', 'like', "%{$search}%");
                })
                ->orderBy($this->sortBy, $this->sortDirection);
            
            $items = $paginationService->paginate($query, $this->perPage, $this->currentPage);
        } else {
            $query = City::with('country')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('country', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                })
                ->orderBy($this->sortBy, $this->sortDirection);
            
            $items = $paginationService->paginate($query, $this->perPage, $this->currentPage);
        }

        return view('livewire.countries-and-cities.index', [
            'items' => $items,
            'paginationMeta' => $paginationService->getPaginationMeta($items),
        ]);
    }
}
