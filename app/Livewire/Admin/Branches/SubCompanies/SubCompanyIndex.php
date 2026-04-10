<?php

namespace App\Livewire\Admin\Branches\SubCompanies;

use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\SubCompany;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.flight')]
class SubCompanyIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected string $paginationTheme = 'tailwind';

    public Company $company;
    public CompanyBranch $branch;

    public bool $createModalOpen = false;
    public bool $editModalOpen = false;
    public ?int $editingSubCompanyId = null;

    public string $name = '';
    public string $code = '';
    public string $country = '';
    public string $city = '';
    public ?string $address = null;
    public ?string $phone = null;
    public string $email = '';
    public $logo = null;
    public ?string $existing_logo_path = null;
    public bool $is_active = true;

    public function mount(Company $company, CompanyBranch $branch): void
    {
        abort_unless($branch->company_id === $company->id, 404);

        $this->company = $company;
        $this->branch = $branch;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'is_active' => ['required', Rule::in([true, false, 0, 1, '0', '1'])],
        ];
    }

    private function resetForm(): void
    {
        $this->editingSubCompanyId = null;
        $this->name = '';
        $this->code = '';
        $this->country = '';
        $this->city = '';
        $this->address = null;
        $this->phone = null;
        $this->email = '';
        $this->logo = null;
        $this->existing_logo_path = null;
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

    public function createSubCompany(): void
    {
        $validated = $this->validate();

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->storePublicly('subcompany-logos', 'public');
        }

        SubCompany::query()->create([
            'company_id' => $this->company->id,
            'company_branch_id' => $this->branch->id,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
            'logo_path' => $logoPath,
            'is_active' => (bool) $validated['is_active'],
        ]);

        session()->flash('status', 'Sub company created successfully.');
        $this->closeCreate();
        $this->resetForm();
        $this->resetPage();
    }

    public function openEdit(int $subCompanyId): void
    {
        $subCompany = SubCompany::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->findOrFail($subCompanyId);

        $this->editingSubCompanyId = $subCompany->id;
        $this->name = (string) $subCompany->name;
        $this->code = (string) $subCompany->code;
        $this->country = (string) $subCompany->country;
        $this->city = (string) $subCompany->city;
        $this->address = $subCompany->address;
        $this->phone = $subCompany->phone;
        $this->email = (string) $subCompany->email;
        $this->logo = null;
        $this->existing_logo_path = $subCompany->logo_path;
        $this->is_active = (bool) $subCompany->is_active;

        $this->resetErrorBag();
        $this->editModalOpen = true;
    }

    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->editingSubCompanyId = null;
        $this->resetErrorBag();
    }

    public function removeLogo(): void
    {
        $this->logo = null;
        $this->existing_logo_path = null;
    }

    public function updateSubCompany(): void
    {
        if (!$this->editingSubCompanyId) {
            return;
        }

        $validated = $this->validate();

        $subCompany = SubCompany::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->findOrFail($this->editingSubCompanyId);

        $logoPath = $subCompany->logo_path;
        if ($this->existing_logo_path === null) {
            $logoPath = null;
        }
        if ($this->logo) {
            $logoPath = $this->logo->storePublicly('subcompany-logos', 'public');
        }

        $subCompany->forceFill([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
            'logo_path' => $logoPath,
            'is_active' => (bool) $validated['is_active'],
        ])->save();

        session()->flash('status', 'Sub company updated successfully.');
        $this->closeEdit();
    }

    public function toggleActive(int $subCompanyId): void
    {
        $subCompany = SubCompany::query()
            ->where('company_id', $this->company->id)
            ->where('company_branch_id', $this->branch->id)
            ->findOrFail($subCompanyId);

        $subCompany->forceFill(['is_active' => !(bool) $subCompany->is_active])->save();
    }

    public function render()
    {
        return view('livewire.admin.branches.subcompanies.index', [
            'subCompanies' => SubCompany::query()
                ->where('company_id', $this->company->id)
                ->where('company_branch_id', $this->branch->id)
                ->latest('id')
                ->paginate(10),
        ]);
    }
}
