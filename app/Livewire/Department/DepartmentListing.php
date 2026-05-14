<?php

namespace App\Livewire\Department;

use App\Models\Department;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class DepartmentListing extends Component
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

    public function toggleActive(int $departmentId): void
    {
        $department = Department::findOrFail($departmentId);
        $newStatus = $department->status === 'active' ? 'inactive' : 'active';
        $department->update(['status' => $newStatus]);
    }

    public function delete(int $departmentId): void
    {
        $department = Department::findOrFail($departmentId);
        $department->delete();
        session()->flash('status', 'Department deleted successfully.');
    }

    public function render(PaginationService $paginationService, TenantContext $tenantContext)
    {
        $search = trim($this->search);

        $query = Department::query()
            ->with('division')
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($this->companyId, function ($query) {
                $query->where('company_id', $this->companyId);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $departments = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.department.department-listing', [
            'departments' => $departments,
            'paginationMeta' => $paginationService->getPaginationMeta($departments),
        ]);
    }
}
