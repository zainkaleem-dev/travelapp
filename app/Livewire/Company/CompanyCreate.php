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
    public ?string $company_type = null;
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
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'company_logo' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'slug' => ['required', 'string', 'max:255', 'unique:companies,slug', 'alpha_dash'],
            'company_type' => ['required', Rule::in(['TMC', 'Corporate'])],
            'registration_number' => ['required', 'string', 'max:50'],
            'founded_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'status' => ['required', Rule::in(['active', 'inactive'])],


            // Other fields
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Please provide a valid name for your company.',
            'company_name.min' => 'Company name must be at least 3 characters.',
            'slug.required' => 'A unique ID/Slug is required for the system.',
            'slug.unique' => 'This ID is already taken. Try a different one.',
            'slug.alpha_dash' => 'The ID can only contain letters, numbers, dashes and underscores.',
            'company_logo.required' => 'A company logo is required.',
            'company_logo.image'    => 'The logo must be an image file.',
            'company_logo.max' => 'The logo size must not exceed 2MB.',
            'founded_year.integer' => 'Please enter a valid year (e.g., 2024).',
            'founded_year.max' => 'The founded year cannot be in the future.',
            'status.required' => 'You must select a status for the company.',
        ];
    }

    public function save()
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
            'parent_id' => auth()->user()->can('Manage Global System') ? null : auth()->user()->company_id,
            'settings' => [
                'logo_path' => $logoPath,
            ],
        ]);

        session()->flash('status', 'Company created successfully.');
        return $this->redirect(route('admin.companies.index'));
    }

    public function render()
    {
        return view('livewire.company.create');
    }
}
