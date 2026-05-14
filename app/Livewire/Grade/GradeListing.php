<?php

namespace App\Livewire\Grade;

use App\Models\Grade;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class GradeListing extends Component
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

    public function toggleActive(int $gradeId): void
    {
        $grade = Grade::findOrFail($gradeId);
        $newStatus = $grade->status === 'active' ? 'inactive' : 'active';
        $grade->update(['status' => $newStatus]);
    }

    public function delete(int $gradeId): void
    {
        $grade = Grade::findOrFail($gradeId);
        $grade->delete();
        session()->flash('status', 'Grade deleted successfully.');
    }

    public function render(PaginationService $paginationService, TenantContext $tenantContext)
    {
        $search = trim($this->search);

        $query = Grade::query()
            ->with('department')
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($this->companyId, function ($query) {
                $query->where('company_id', $this->companyId);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $grades = $paginationService->paginate($query, $this->perPage, $this->currentPage);

        return view('livewire.grade.grade-listing', [
            'grades' => $grades,
            'paginationMeta' => $paginationService->getPaginationMeta($grades),
        ]);
    }
}
