<?php
 
namespace App\Livewire\Branch\SubCompanies;
 
use App\Models\CompanyBranch;
use App\Models\SubCompany;
use App\Support\TenantContext;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
 
#[Layout('layouts.flight')]
class SubCompanyIndex extends Component
{
    use WithPagination;
    use WithFileUploads;
 
    protected string $paginationTheme = 'tailwind';
 
    public CompanyBranch $branch;
    public bool $canManageSubCompanies = true;
 
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
 
    public string $admin_email = '';
    public string $admin_password = '';
    public ?string $admin_name = null;
 
    public function mount(TenantContext $tenantContext): void
    {
        $companyId = (int) ($tenantContext->companyId() ?? 0);
        abort_unless($companyId > 0, 403);
 
        $branchId = (int) (auth()->user()?->company_branch_id ?? 0);
        abort_unless($branchId > 0, 403);
 
        $branch = CompanyBranch::query()
            ->where('company_id', $companyId)
            ->findOrFail($branchId);
 
        $this->branch = $branch;
    }
 
    private function baseRules(): array
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
 
    private function adminRules(): array
    {
        return [
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_name' => ['nullable', 'string', 'max:255'],
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
 
        $this->admin_email = '';
        $this->admin_password = '';
        $this->admin_name = null;
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
        $validated = $this->validate(array_merge($this->baseRules(), $this->adminRules()));

        DB::transaction(function () use ($validated) {
            $logoPath = null;
            if ($this->logo) {
                $logoPath = $this->logo->storePublicly('subcompany-logos', 'public');
            }
 
            $subCompany = SubCompany::query()->create([
                'company_id' => $this->branch->company_id,
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
 
            $subCompanyAdmin = User::query()->create([
                'company_id' => (int) $subCompany->company_id,
                'company_branch_id' => (int) $subCompany->company_branch_id,
                'sub_company_id' => (int) $subCompany->id,
                'first_name' => $validated['admin_name'] ?: null,
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'email_verified_at' => now(),
            ]);
 
            $subCompanyAdminRole = Role::findOrCreate('subcompany_admin', 'web');
            $subCompanyAdmin->assignRole($subCompanyAdminRole);
        });

        session()->flash('status', 'Sub company and admin created successfully.');
        $this->closeCreate();
        $this->resetForm();
        $this->resetPage();
    }
 
    public function openEdit(int $subCompanyId): void
    {
        $subCompany = SubCompany::query()
            ->where('company_id', $this->branch->company_id)
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
        if ($this->editingSubCompanyId === null) {
            return;
        }

        try {
            $validated = $this->validate($this->baseRules());

            $subCompany = SubCompany::query()
                ->where('company_id', $this->branch->company_id)
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
            $this->resetPage();
        } catch (\Throwable $e) {
            report($e);
            $this->addError('update', 'Update failed. Please check the form fields and try again.');
        }
    }
 
    public function toggleActive(int $subCompanyId): void
    {
        $subCompany = SubCompany::query()
            ->where('company_id', $this->branch->company_id)
            ->where('company_branch_id', $this->branch->id)
            ->findOrFail($subCompanyId);
 
        $subCompany->forceFill(['is_active' => !(bool) $subCompany->is_active])->save();
    }
 
    public function render()
    {
        return view('livewire.branch.subcompanies.index', [
            'company' => $this->branch->company,
            'branch' => $this->branch,
            'subCompanies' => SubCompany::query()
                ->where('company_id', $this->branch->company_id)
                ->where('company_branch_id', $this->branch->id)
                ->latest('id')
                ->paginate(10),
        ]);
    }
}
