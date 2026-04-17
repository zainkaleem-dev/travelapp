<?php

namespace App\Livewire\Branch;

use App\Models\Branch;
use App\Models\Company;
use App\Services\PaginationService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class BranchListing extends Component
{
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public string $companyFilter = '';
    public string $statusFilter = '';
    public string $routePrefix = 'superadmin';

    public function mount()
    {
        $this->authorize('View Branch');
        $this->currentPage = (int) request()->query('page', 1);

        if (request()->is('company*')) {
            $this->routePrefix = 'company';
        }
    }

    #[\Livewire\Attributes\On('paginationGoTo')]
    public function goToPage($page): void
    {
        $this->currentPage = (int) $page;
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedPerPage(): void
    {
        $this->currentPage = 1;
    }

    public function updatedCompanyFilter(): void
    {
        $this->currentPage = 1;
    }

    public function updatedStatusFilter(): void
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

    public function toggleActive(int $branchId): void
    {
        $this->authorize('Edit Branch');
        $branch = Branch::query()->findOrFail($branchId);
        $newStatus = $branch->status === 'active' ? 'inactive' : 'active';
        $branch->update(['status' => $newStatus]);
    }

    public function deleteBranch(int $branchId): void
    {
        $this->authorize('Delete Branch');
        $branch = Branch::query()->findOrFail($branchId);
        $branch->delete();

        session()->flash('status', 'Branch deleted successfully.');
    }

    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);

        $query = Branch::query()
            ->with('company')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('branches.company_id', $this->companyFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('branches.status', $this->statusFilter);
            })
            ->leftJoin('companies', 'branches.company_id', '=', 'companies.id')
            ->select('branches.*')
            ->orderBy($this->sortBy === 'company' ? 'companies.name' : 'branches.' . $this->sortBy, $this->sortDirection);

        $branches = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.branch.listing', [
            'branches' => $branches,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'paginationMeta' => $paginationService->getPaginationMeta($branches),
        ]);
    }
}
