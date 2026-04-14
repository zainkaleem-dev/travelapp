<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Company;
use App\Services\PaginationService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
 
#[Layout('layouts.flight')]
class CompanyIndex extends Component
{
    use WithFileUploads;
 
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
 
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
 
    public function toggleActive(int $companyId): void
    {
        $company = Company::query()->findOrFail($companyId);
        $newStatus = $company->status === 'active' ? 'inactive' : 'active';
        $company->update(['status' => $newStatus]);
    }
 
    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);
 
        $query = Company::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_type', 'like', "%{$search}%")
                        ->orWhere('legal_name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest('id');
 
        $companies = $paginationService->paginate($query, $this->perPage, $this->currentPage);
 
        return view('livewire.admin.index', [
            'companies' => $companies,
            'paginationMeta' => $paginationService->getPaginationMeta($companies),
        ]);
    }
}
