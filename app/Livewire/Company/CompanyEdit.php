<?php
 
namespace App\Livewire\Company;
 
use App\Models\Company;
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\Rule; 
use Livewire\Attributes\Layout; 
use Livewire\Component; 
use Livewire\WithFileUploads; 
 
#[Layout('layouts.flight')]
class CompanyEdit extends Component
{
    use WithFileUploads;
 
    public int $companyId;
    public Company $company;
 
    // Core Identity
    public string $company_name = '';
    public string $slug = '';
    public string $company_type = '';
    public ?string $legal_name = null;
    public ?string $registration_number = null;
    public ?string $tax_number = null;
    public $company_logo = null;
    public ?string $existing_logo_path = null;
    public ?int $founded_year = null;
    public ?string $description = null;
 
    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;
 
    public function mount(int $id): void
    {
        $this->companyId = $id;
        $this->company = Company::query()->findOrFail($id);
 
        $this->company_name = $this->company->name;
        $this->slug = $this->company->slug;
        $this->company_type = $this->company->company_type ?? '';
        $this->legal_name = $this->company->legal_name;
        $this->registration_number = $this->company->registration_number;
        $this->tax_number = $this->company->tax_number;
        $this->existing_logo_path = $this->company->settings['logo_path'] ?? null;
        $this->founded_year = $this->company->founded_year;
        $this->description = $this->company->description;
        $this->status = $this->company->status;
        $this->notes = $this->company->notes;
    }
 
    public function updatedCompanyName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }
    }
 
    public function removeLogo(): void
    {
        $this->company_logo = null;
        $this->existing_logo_path = null;
    }
 
    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('companies', 'slug')->ignore($this->companyId)],
            'company_type' => ['nullable', Rule::in(['TMC', 'Corporate'])],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
 
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ];
    }
 
    public function save()
    {
        $validated = $this->validate();
 
        $logoPath = $this->existing_logo_path;
        if ($this->company_logo) {
            $logoPath = $this->company_logo->storePublicly('company-logos', 'public');
        } elseif ($this->existing_logo_path === null) {
            $logoPath = null;
        }
 
        DB::transaction(function () use ($validated, $logoPath) {
            $this->company->update([
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
                'settings' => array_merge($this->company->settings ?? [], [
                    'logo_path' => $logoPath,
                ]),
            ]);
        });
 
        session()->flash('status', 'Company updated successfully.');
        return redirect()->route('superadmin.companies.index');
    }
 
    public function render()
    {
        return view('livewire.company.edit');
    }
}
