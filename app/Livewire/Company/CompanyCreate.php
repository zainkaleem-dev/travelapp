<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Illuminate\Support\Str;
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

        $this->registration_number = $this->generateRegistrationNumber($value);
    }

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'company_logo' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'slug' => ['required', 'string', 'max:255', 'unique:companies,slug', 'alpha_dash'],
            'company_type' => ['required', Rule::in(['TMC', 'Corporate'])],
            'registration_number' => ['required', 'string', 'max:50', 'unique:companies,registration_number'],
            'founded_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'status' => ['required', Rule::in(['active', 'inactive'])],


            // Other fields
            'legal_name' => ['nullable', 'string', 'max:255'],
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
            'company_logo.image' => 'The logo must be an image file.',
            'company_logo.max' => 'The logo size must not exceed 2MB.',
            'founded_year.integer' => 'Please enter a valid year (e.g., 2024).',
            'founded_year.max' => 'The founded year cannot be in the future.',
            'status.required' => 'You must select a status for the company.',
        ];
    }

    public function save()
    {
        // 1. Resolve Limit
        $limit = (int) \Laravel\Pennant\Feature::value('companies-quantity');

        // 2. Count Existing based on scope
        $query = Company::query();
        if (auth()->user()->can('Manage Global System')) {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', auth()->user()->company_id);
        }
        $count = $query->count();

        // 3. Prevent save if limit is reached
        if ($count >= $limit) {
            session()->flash('error', "Limit reached. You are only allowed to have more than {$limit} companies.");
            return $this->redirect(route('companies.index'));
        }

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
        return $this->redirect(route('companies.index'));
    }

    public function render()
    {
        return view('livewire.company.create');
    }

    private function generateRegistrationNumber(?string $companyName): string
    {
        $base = Str::of((string) $companyName)->trim()->slug('-')->toString();
        if ($base === '') {
            $base = 'organization';
        }

        // Keep room for hyphen + 4 digits (max:50).
        $base = Str::limit($base, 45, '');

        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidate = "{$base}-{$suffix}";
        } while (Company::where('registration_number', $candidate)->exists());

        return $candidate;
    }
}
