<?php

namespace App\Livewire\Division;

use App\Models\Division;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DivisionListing extends Component
{
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
    public string $statusFilter = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    public ?int $companyId = null;

    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
        $this->currentPage = (int) request()->query('page', 1);
    }

    public function goToPage($page): void
    {
        $this->currentPage = (int) $page;
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
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

    public function toggleActive(int $divisionId): void
    {
        $division = Division::findOrFail($divisionId);
        $newStatus = $division->status === 'active' ? 'inactive' : 'active';
        $division->update(['status' => $newStatus]);
    }

    public function delete(int $divisionId): void
    {
        $division = Division::findOrFail($divisionId);
        $division->delete();
        session()->flash('status', 'Division deleted successfully.');
    }

    public function render(PaginationService $paginationService, TenantContext $tenantContext)
    {
        $search = trim($this->search);

        $query = Division::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($this->companyId, function ($query) {
                $query->where('company_id', $this->companyId);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $divisions = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.division.division-listing', [
            'divisions' => $divisions,
            'paginationMeta' => $paginationService->getPaginationMeta($divisions),
        ]);
    }
}
