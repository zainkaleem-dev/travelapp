<?php
 
namespace App\Livewire\Branch;
 
use App\Models\Branch;
use App\Services\PaginationService;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
#[Layout('layouts.flight')]
class BranchListing extends Component
{
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
 
    public function toggleActive(int $branchId): void
    {
        $branch = Branch::query()->findOrFail($branchId);
        $newStatus = $branch->status === 'active' ? 'inactive' : 'active';
        $branch->update(['status' => $newStatus]);
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
            ->latest('id');
 
        $branches = $paginationService->paginate($query, $this->perPage, $this->currentPage);
 
        return view('livewire.branch.listing', [
            'branches' => $branches,
            'paginationMeta' => $paginationService->getPaginationMeta($branches),
        ]);
    }
}
