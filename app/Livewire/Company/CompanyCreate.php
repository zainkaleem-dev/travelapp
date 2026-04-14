<?php
 
namespace App\Livewire\Company;
 
use App\Models\Company;
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
        ];
    }
 
    public function save(): void
    {
        $validated = $this->validate();

        $logoPath = null;
        if ($this->company_logo) {
            $logoPath = $this->company_logo->storePublicly('company-logos', 'public');
        }

        Company::query()->create([
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

        session()->flash('status', 'Company created successfully.');
        $this->reset();
    }
 
    public function render()
    {
        return view('livewire.company.create');
    }
}
