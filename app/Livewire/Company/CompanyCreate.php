<?php

namespace App\Livewire\Company;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
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
    public array $attachments = [];
    public array $attachmentNames = []; // Map of index => custom name
    public ?int $founded_year = null;
    public ?string $description = null;

    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;

    public array $allowed_types = [];

    public function mount()
    {
        if (auth()->user()->can('Manage Global System')) {
            $this->allowed_types = ['TMC', 'Corporate'];
        } else {
            $myCompany = auth()->user()->company;
            if ($myCompany?->company_type === 'Corporate') {
                $this->allowed_types = ['TMC'];
                $this->company_type = 'TMC';
            } elseif ($myCompany?->company_type === 'TMC') {
                $this->allowed_types = ['Corporate'];
                $this->company_type = 'Corporate';
            } else {
                $this->allowed_types = ['TMC', 'Corporate'];
            }
        }
    }


    public function updatedCompanyName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }

        // Sync with first branch if it's empty or matches previous company name
        if (isset($this->branches[0]) && (empty($this->branches[0]['name']) || $this->branches[0]['name'] === $this->company_name)) {
            $this->branches[0]['name'] = $value;
            $this->updateBranchFields(0, $value);
        }

        $this->registration_number = $this->generateRegistrationNumber($value);
    }


    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'company_logo' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:20480'],
            'slug' => ['required', 'string', 'max:255', 'unique:companies,slug', 'alpha_dash'],
            'company_type' => ['required', Rule::in($this->allowed_types)],
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
        // 1. Resolve Limit (Optional check, user said "no limit" but Pennant might still be active)
        $limit = (int) \Laravel\Pennant\Feature::value('companies-quantity');
        $query = Company::query();
        if (auth()->user()->can('Manage Global System')) {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', auth()->user()->company_id);
        }
        if ($query->count() >= $limit) {
            session()->flash('error', "Limit reached. You are only allowed to have more than {$limit} companies.");
            return $this->redirect(route('companies.index'));
        }

        $validated = $this->validate();

        $company = DB::transaction(function () use ($validated) {
            $logoPath = null;
            if ($this->company_logo) {
                $logoPath = $this->company_logo->storePublicly('company-logos', 'public');
            }

            $company = Company::query()->create([
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

            // Attachments Logic
            if ($this->company_logo instanceof UploadedFile && $logoPath) {
                $company->attachments()->create([
                    'disk' => 'public',
                    'path' => $logoPath,
                    'original_name' => $this->company_logo->getClientOriginalName(),
                    'mime_type' => $this->company_logo->getClientMimeType(),
                    'size' => $this->company_logo->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            foreach ($this->attachments as $index => $attachment) {
                if (!$attachment instanceof UploadedFile) continue;
                
                $customName = $this->attachmentNames[$index] ?? $attachment->getClientOriginalName();
                $path = $attachment->storePublicly('company-attachments', 'public');
                
                $company->attachments()->create([
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $customName,
                    'mime_type' => $attachment->getClientMimeType(),
                    'size' => $attachment->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            return $company;
        });

        session()->flash('status', 'Company created successfully. You can now add branches.');
        return $this->redirect(route('companies.create-branches', ['id' => $company->id]));
    }

    public function render()
    {
        return view('livewire.company.create');
    }

    public function removeAttachment(int $index): void
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            unset($this->attachmentNames[$index]);
            $this->attachments = array_values($this->attachments);
            $this->attachmentNames = array_values($this->attachmentNames);
        }
    }

    public function renameAttachment(int $index, string $newName): void
    {
        if (isset($this->attachments[$index])) {
            $this->attachmentNames[$index] = $newName;
        }
    }

    public function downloadAttachment(int $index)
    {
        if (isset($this->attachments[$index])) {
            return response()->download($this->attachments[$index]->getRealPath(), $this->attachmentNames[$index] ?? $this->attachments[$index]->getClientOriginalName());
        }
    }

    private function generateRegistrationNumber(?string $companyName): string
    {
        $base = Str::of((string) $companyName)->trim()->slug('-')->toString();
        if ($base === '') $base = 'organization';
        $base = Str::limit($base, 45, '');
        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidate = "{$base}-{$suffix}";
        } while (Company::where('registration_number', $candidate)->exists());
        return $candidate;
    }
}
