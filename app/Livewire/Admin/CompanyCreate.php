<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule; 
use Livewire\Attributes\Layout; 
use Livewire\Component; 
use Livewire\WithFileUploads; 
use Spatie\Permission\Models\Role;

#[Layout('layouts.flight')]
class CompanyCreate extends Component
{
    use WithFileUploads;

    public string $company_name = '';
    public string $company_type = '';
    public $company_logo = null;

    public string $admin_email = '';
    public string $admin_password = '';
    public ?string $admin_name = null;

    public ?string $company_email = null;
    public ?string $phone = null;
    public ?string $country = null;
    public ?string $subscription_plan = null;
    public ?int $company_limit = null;

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'company_type' => ['required', Rule::in([
                'TMC - Alma Travel',
                'Corporate - Nahdi',
                'Corporate - STC',
                'TMC - Global Travel',
            ])],
            'company_logo' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,svg'],

            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_name' => ['nullable', 'string', 'max:255'],

            'company_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
            'subscription_plan' => ['nullable', 'string', 'max:255'],
            'company_limit' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $logoPath = null;
        if ($this->company_logo) {
            $logoPath = $this->company_logo->storePublicly('company-logos', 'public');
        }

        DB::transaction(function () use ($validated, $logoPath) { 
            $company = Company::query()->create([ 
                'name' => $validated['company_name'], 
                'type' => $validated['company_type'], 
                'logo_path' => $logoPath, 
                'is_active' => true,
                'email' => $validated['company_email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'country' => $validated['country'] ?? null,
                'subscription_plan' => $validated['subscription_plan'] ?? null,
                'company_limit' => $validated['company_limit'] ?? null, 
            ]); 
 
            $adminUser = User::query()->create([ 
                'company_id' => $company->id, 
                'first_name' => $validated['admin_name'] ?: null, 
                'email' => $validated['admin_email'], 
                'password' => Hash::make($validated['admin_password']), 
                'email_verified_at' => now(),
            ]); 
 
            $companyAdminRole = Role::findOrCreate('company_admin', 'web');
            $adminUser->assignRole($companyAdminRole);
        }); 

        session()->flash('status', 'Company and admin user created successfully.');
        $this->reset([
            'company_name',
            'company_type',
            'company_logo',
            'admin_email',
            'admin_password',
            'admin_name',
            'company_email',
            'phone',
            'country',
            'subscription_plan',
            'company_limit',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.create');
    }
}
