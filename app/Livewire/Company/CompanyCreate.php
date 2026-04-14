<?php
 
namespace App\Livewire\Company;
 
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule; 
use Livewire\Attributes\Layout; 
use Livewire\Component; 
use Livewire\WithFileUploads; 
 
#[Layout('layouts.flight')]
class CompanyCreate extends Component
{
    use WithFileUploads;
 
    // Core Identity
    public string $company_name = '';
    public string $slug = '';
    public string $company_type = '';
    public ?string $legal_name = null;
    public ?string $registration_number = null;
    public ?string $tax_number = null;
    public $company_logo = null;
    public ?int $founded_year = null;
    public ?string $description = null;
 
    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;
 
    // Admin Account
    public string $admin_email = '';
    public string $admin_password = '';
    public ?string $admin_name = null;
 
    public function updatedCompanyName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }
    }
 
    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:companies,slug'],
            'company_type' => ['nullable', Rule::in(['TMC', 'Corporate'])],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
 
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
 
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_name' => ['nullable', 'string', 'max:255'],
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
                'slug' => $validated['slug'],
                'company_type' => $validated['company_type'],
                'legal_name' => $validated['legal_name'],
                'registration_number' => $validated['registration_number'],
                'tax_number' => $validated['tax_number'],
                'founded_year' => $validated['founded_year'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'settings' => [
                    'logo_path' => $logoPath,
                ],
            ]);
 
            $adminUser = User::query()->create([
                'company_id' => $company->id,
                'name' => $validated['admin_name'] ?: 'Admin User',
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'email_verified_at' => now(),
            ]);
 
        });
 
        session()->flash('status', 'Company and admin user created successfully.');
        $this->reset();
    }
 
    public function render()
    {
        return view('livewire.company.create');
    }
}
