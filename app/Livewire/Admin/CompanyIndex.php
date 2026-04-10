<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Services\PaginationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

    public ?int $editingCompanyId = null;
    public bool $editModalOpen = false;

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

    public string $company_name = '';
    public string $company_type = '';
    public $company_logo = null;
    public ?string $existing_logo_path = null;
    public ?string $company_email = null;
    public ?string $phone = null;
    public ?string $country = null;
    public ?string $subscription_plan = null;
    public ?int $company_limit = null;

    public string $admin_email = '';
    public string $admin_password = '';
    public ?string $admin_name = null;

    protected function rules(): array
    {
        return [
            'company_logo' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_type' => ['required', Rule::in([
                'TMC - Alma Travel',
                'Corporate - Nahdi',
                'Corporate - STC',
                'TMC - Global Travel',
            ])],
            'company_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
            'subscription_plan' => ['nullable', 'string', 'max:255'],
            'company_limit' => ['nullable', 'integer', 'min:1'],

            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function openEdit(int $companyId): void
    {
        $company = Company::query()->with(['users' => fn ($q) => $q->orderBy('id')])->findOrFail($companyId);
        $adminUser = $company->users->first();

        $this->editingCompanyId = $company->id;
        $this->company_name = (string) $company->name;
        $this->company_type = (string) $company->type;
        $this->company_logo = null;
        $this->existing_logo_path = $company->logo_path;
        $this->company_email = $company->email;
        $this->phone = $company->phone;
        $this->country = $company->country;
        $this->subscription_plan = $company->subscription_plan;
        $this->company_limit = $company->company_limit;

        $this->admin_email = (string) ($adminUser?->email ?? '');
        $this->admin_password = '';
        $this->admin_name = $adminUser?->first_name;

        $this->resetErrorBag();
        $this->editModalOpen = true;
    }

    public function closeEdit(): void
    {
        $this->editModalOpen = false;
        $this->editingCompanyId = null;
        $this->existing_logo_path = null;
        $this->resetErrorBag();
    }

    public function removeLogo(): void
    {
        $this->company_logo = null;
        $this->existing_logo_path = null;
    }

    public function updateCompany(): void
    {
        if (!$this->editingCompanyId) {
            return;
        }

        $company = Company::query()->with(['users' => fn ($q) => $q->orderBy('id')])->findOrFail($this->editingCompanyId);
        $adminUser = $company->users->first();
        if (!$adminUser) {
            $this->addError('admin_email', 'Admin user not found for this company.');
            return;
        }

        $validated = $this->validate(array_merge($this->rules(), [
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($adminUser->id)],
        ]));

        $logoPath = $company->logo_path;
        if ($this->existing_logo_path === null) {
            $logoPath = null;
        }
        if ($this->company_logo) {
            $logoPath = $this->company_logo->storePublicly('company-logos', 'public');
        }

        DB::transaction(function () use ($company, $adminUser, $validated, $logoPath) {
            $company->forceFill([
                'name' => $validated['company_name'],
                'type' => $validated['company_type'],
                'logo_path' => $logoPath,
                'email' => $validated['company_email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'country' => $validated['country'] ?? null,
                'subscription_plan' => $validated['subscription_plan'] ?? null,
                'company_limit' => $validated['company_limit'] ?? null,
            ])->save();

            $adminUser->forceFill([
                'first_name' => $validated['admin_name'] ?: null,
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
            ])->save();
        });

        session()->flash('status', 'Company updated successfully.');
        $this->closeEdit();
    }

    public function toggleActive(int $companyId): void
    {
        $company = Company::query()->findOrFail($companyId);
        $company->forceFill(['is_active' => !(bool) $company->is_active])->save();
    }

    public function render(PaginationService $paginationService)
    {
        $search = trim($this->search);

        $query = Company::query()
            ->with(['users' => fn ($q) => $q->orderBy('id')])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%")
                        ->orWhere('subscription_plan', 'like', "%{$search}%");
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
