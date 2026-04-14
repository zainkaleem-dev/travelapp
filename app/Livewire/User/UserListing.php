<?php
 
namespace App\Livewire\User;
 
use App\Models\User;
use App\Services\PaginationService;
use App\Support\TenantContext;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
#[Layout('layouts.flight')]
class UserListing extends Component
{
    public string $search = '';
    public int $currentPage = 1;
    public int $perPage = 10;
 
    public function mount(): void
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
 
    public function delete(int $userId, TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
 
        $user = User::query()
            ->withoutRole('super_admin')
            ->findOrFail($userId);
 
        $user->delete();
        session()->flash('status', 'User deleted successfully.');
    }
 
    public function render(PaginationService $paginationService, TenantContext $tenantContext)
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        $search = trim($this->search);
 
        $query = User::query()
            ->withoutRole('super_admin')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('id');
 
        $users = $paginationService->paginate($query, $this->perPage, $this->currentPage);
 
        return view('livewire.user.listing', [
            'users' => $users,
            'paginationMeta' => $paginationService->getPaginationMeta($users),
        ]);
    }
}
