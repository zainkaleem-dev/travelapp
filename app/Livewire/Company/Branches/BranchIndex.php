<?php
 
namespace App\Livewire\Company\Branches; 
 
use App\Models\CompanyBranch; 
use App\Models\User;
use App\Support\TenantContext; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; 
use Livewire\Attributes\Layout; 
use Livewire\Component; 
use Livewire\WithPagination; 
use Spatie\Permission\Models\Role;
 
#[Layout('layouts.flight')]
class BranchIndex extends Component
{
    use WithPagination;
 
    public ?string $companyName = null;
 
    public bool $createModalOpen = false;
    public bool $editModalOpen = false;
    public ?int $editingBranchId = null;
 
    public string $name = '';
    public ?string $code = null;
    public ?string $country = null;
    public ?string $city = null;
    public ?string $address = null;
    public ?string $phone = null; 
    public ?string $email = null; 
    public bool $is_active = true; 
 
    public string $admin_email = '';
    public string $admin_password = '';
    public ?string $admin_name = null;
 
    public function mount(TenantContext $tenantContext): void
    {
        $company = $tenantContext->currentCompany(request());
        $this->companyName = $company?->name;
    }
 
    protected function rules(): array 
    { 
        return [ 
            'name' => ['required', 'string', 'max:255'], 
            'code' => ['nullable', 'string', 'max:255'], 
            'country' => ['nullable', 'string', 'max:255'], 
            'city' => ['nullable', 'string', 'max:255'], 
            'address' => ['nullable', 'string', 'max:500'], 
            'phone' => ['nullable', 'string', 'max:50'], 
            'email' => ['nullable', 'email', 'max:255'], 
            'is_active' => ['required', Rule::in([true, false, 0, 1, '0', '1'])], 
 
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_name' => ['nullable', 'string', 'max:255'],
        ]; 
    } 
 
    public function openCreate(): void
    {
        $this->resetForm();
        $this->createModalOpen = true;
    }
 
    public function closeCreate(): void
    {
        $this->createModalOpen = false;
        $this->resetErrorBag();
    }
 
    public function createBranch(): void 
    { 
        $validated = $this->validate(); 
 
        DB::transaction(function () use ($validated) {
            $branch = CompanyBranch::query()->create([ 
                'name' => $validated['name'], 
                'code' => $validated['code'] ?? null, 
                'country' => $validated['country'] ?? null, 
                'city' => $validated['city'] ?? null, 
                'address' => $validated['address'] ?? null, 
                'phone' => $validated['phone'] ?? null, 
                'email' => $validated['email'] ?? null, 
                'is_active' => (bool) $validated['is_active'], 
            ]);
 
            $branchAdmin = User::query()->create([
                'company_id' => (int) $branch->company_id,
                'company_branch_id' => (int) $branch->id,
                'first_name' => $validated['admin_name'] ?: null,
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'email_verified_at' => now(),
            ]);
 
            $branchAdminRole = Role::findOrCreate('branch_admin', 'web');
            $branchAdmin->assignRole($branchAdminRole);
        });
 
        session()->flash('status', 'Branch and branch admin created successfully.'); 
        $this->closeCreate(); 
        $this->resetForm(); 
        $this->resetPage(); 
    } 
 
    public function openEdit(int $branchId): void
    {
        $branch = CompanyBranch::query()->findOrFail($branchId);
 
        $this->editingBranchId = $branch->id;
        $this->name = (string) $branch->name;
        $this->code = $branch->code;
        $this->country = $branch->country;
        $this->city = $branch->city;
        $this->address = $branch->address;
        $this->phone = $branch->phone;
        $this->email = $branch->email;
        $this->is_active = (bool) $branch->is_active;
 
        $this->resetErrorBag();
        $this->editModalOpen = true;
    }
 
    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->editingBranchId = null;
        $this->resetErrorBag();
    }
 
    public function updateBranch(): void
    {
        if (!$this->editingBranchId) {
            return;
        }
 
        $validated = $this->validate();
 
        $branch = CompanyBranch::query()->findOrFail($this->editingBranchId);
 
        $branch->forceFill([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ])->save();
 
        session()->flash('status', 'Branch updated successfully.');
        $this->closeEdit();
    }
 
    public function toggleActive(int $branchId): void
    {
        $branch = CompanyBranch::query()->findOrFail($branchId);
        $branch->forceFill(['is_active' => !(bool) $branch->is_active])->save();
    }
 
    private function resetForm(): void 
    { 
        $this->editingBranchId = null; 
        $this->name = ''; 
        $this->code = null; 
        $this->country = null; 
        $this->city = null; 
        $this->address = null; 
        $this->phone = null; 
        $this->email = null; 
        $this->is_active = true; 
 
        $this->admin_email = '';
        $this->admin_password = '';
        $this->admin_name = null;
    } 
 
    public function render()
    {
        return view('livewire.company.branches.index', [
            'branches' => CompanyBranch::query()
                ->latest('id')
                ->paginate(10),
        ]);
    }
}
