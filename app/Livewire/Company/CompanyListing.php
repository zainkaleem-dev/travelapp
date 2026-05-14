<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Services\PaginationService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.flight')]
class CompanyListing extends Component
{
    use WithFileUploads;

    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public string $typeFilter = '';
    public string $statusFilter = '';

    public function mount()
    {

        $this->currentPage = (int) request()->query('page', 1);

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

    public function updatedTypeFilter(): void
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

    public function toggleActive(int $companyId): void
    {
        $company = Company::query()->findOrFail($companyId);
        $newStatus = $company->status === 'active' ? 'inactive' : 'active';
        $company->update(['status' => $newStatus]);
    }

    public function deleteCompany(int $companyId): void
    {
        $company = Company::query()->findOrFail($companyId);
        $company->delete();

        session()->flash('status', 'Company deleted successfully.');
    }

    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);

        $query = Company::query()
            ->when(!auth()->user()->can('Manage Global System'), function ($query) {
                // Organization Admin sees branches/sub-companies they created
                $query->where('parent_id', auth()->user()->company_id);
            })
            ->when(auth()->user()->company_id, function ($query) {
                $query->where('id', '!=', auth()->user()->company_id);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_type', 'like', "%{$search}%")
                        ->orWhere('legal_name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('company_type', $this->typeFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $companies = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.company.listing', [
            'companies' => $companies,
            'paginationMeta' => $paginationService->getPaginationMeta($companies),
        ]);
    }
}
