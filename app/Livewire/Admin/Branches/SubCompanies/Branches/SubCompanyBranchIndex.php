<?php

namespace App\Livewire\Admin\Branches\SubCompanies\Branches;

use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\SubCompany;
use App\Models\SubCompanyBranch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class SubCompanyBranchIndex extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public Company $company;
    public CompanyBranch $branch;
    public SubCompany $subCompany;

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

    public function mount(Company $company, CompanyBranch $branch, SubCompany $subCompany): void
    {
        abort_unless($branch->company_id === $company->id, 404);
        abort_unless($subCompany->company_id === $company->id, 404);
        abort_unless((int) $subCompany->company_branch_id === (int) $branch->id, 404);

        $this->company = $company;
        $this->branch = $branch;
        $this->subCompany = $subCompany;
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
        ];
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
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
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

        SubCompanyBranch::query()->create([
            'company_id' => $this->company->id,
            'company_branch_id' => $this->branch->id,
            'sub_company_id' => $this->subCompany->id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ]);

        session()->flash('status', 'Branch created successfully.');
        $this->closeCreate();
        $this->resetForm();
        $this->resetPage();
    }

    public function openEdit(int $branchId): void
    {
        $branch = SubCompanyBranch::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->where('sub_company_id', $this->subCompany->id)
            ->findOrFail($branchId);

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

        $branch = SubCompanyBranch::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->where('sub_company_id', $this->subCompany->id)
            ->findOrFail($this->editingBranchId);

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
        $branch = SubCompanyBranch::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->where('sub_company_id', $this->subCompany->id)
            ->findOrFail($branchId);

        $branch->forceFill(['is_active' => !(bool) $branch->is_active])->save();
    }

    public function render()
    {
        return view('livewire.admin.branches.subcompanies.branches.index', [
            'branches' => SubCompanyBranch::query()
                ->where('company_id', $this->company->id)
                ->where('company_branch_id', $this->branch->id)
                ->where('sub_company_id', $this->subCompany->id)
                ->latest('id')
                ->paginate(10),
        ]);
    }
}
