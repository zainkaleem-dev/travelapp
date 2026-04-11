<?php
 
namespace App\Livewire\Branch\Branches;
 
use App\Models\CompanyBranch;
use App\Support\TenantContext;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
#[Layout('layouts.flight')]
class BranchIndex extends Component
{
    public CompanyBranch $branch;
 
    public bool $editModalOpen = false;
 
    public string $name = '';
    public ?string $code = null;
    public ?string $country = null;
    public ?string $city = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;

    public function mount(TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $branchId = (int) (auth()->user()?->company_branch_id ?? 0);
        abort_unless($branchId > 0, 403);
 
        $this->branch = CompanyBranch::query()
            ->where('company_id', $companyId)
            ->findOrFail($branchId);
 
        $this->fillFormFromBranch();
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
        ];
    }
 
    public function openEdit(): void
    {
        $this->fillFormFromBranch();
        $this->resetErrorBag();
        $this->editModalOpen = true;
    }
 
    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->resetErrorBag();
    }
 
    public function updateBranch(): void
    {
        $validated = $this->validate();
 
        $this->branch->forceFill([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
        ])->save();
 
        $this->branch->refresh();
        session()->flash('status', 'Branch updated successfully.');
        $this->closeEdit();
    }
 
    private function fillFormFromBranch(): void
    {
        $this->name = (string) $this->branch->name;
        $this->code = $this->branch->code;
        $this->country = $this->branch->country;
        $this->city = $this->branch->city;
        $this->address = $this->branch->address;
        $this->phone = $this->branch->phone;
        $this->email = $this->branch->email;
    }

    public function render()
    {
        return view('livewire.branch.branches.index', [
            'company' => $this->branch->company,
            'branch' => $this->branch,
        ]);
    }
}
