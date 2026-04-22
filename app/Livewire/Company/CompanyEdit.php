<?php

namespace App\Livewire\Company;

use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    public ?string $company_type = null;
    public ?string $legal_name = null;
    public ?string $registration_number = null;
    public $company_logo = null;
    public array $attachments = [];
    public ?string $existing_logo_path = null;
    public ?int $founded_year = null;
    public ?string $description = null;
    public ?int $parent_id = null;

    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;

    // Dynamic Branches
    public bool $create_branch = true;
    public array $branches = [];
    public array $branches_to_delete = [];

    public function mount(int $id): void
    {
        $this->companyId = $id;

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        if (!$isSuperAdmin) {
            if (!in_array($this->company->id, $manageableHierarchy)) {
                abort(403, 'You do not have permission to edit this organization (Access denied).');
            }
        }

        $this->company_name = $this->company->name;
        $this->slug = $this->company->slug;
        $this->company_type = $this->company->company_type;
        $this->legal_name = $this->company->legal_name;
        $this->registration_number = $this->company->registration_number;
        $this->existing_logo_path = $this->company->settings['logo_path'] ?? null;
        $this->founded_year = $this->company->founded_year;
        $this->description = $this->company->description;
        $this->status = $this->company->status;
        $this->notes = $this->company->notes;
        $this->parent_id = $this->company->parent_id;

        // Load existing branches
        $this->branches = $this->company->branches()->orderBy('is_main', 'desc')->get()->toArray();
        if (empty($this->branches)) {
            $this->addBranch();
        }
    }

    public function addBranch(): void
    {
        $this->branches[] = [
            'name' => '',
            'code' => '',
            'slug' => '',
            'email' => '',
            'phone' => '',
            'address_line_1' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'latitude' => '0',
            'longitude' => '0',
        ];
    }

    public function removeBranch(int $index): void
    {
        if (isset($this->branches[$index]['id'])) {
            $this->branches_to_delete[] = $this->branches[$index]['id'];
        }

        unset($this->branches[$index]);
        $this->branches = array_values($this->branches);

        if (empty($this->branches)) {
            $this->addBranch();
        }
    }

    public function updatedCompanyName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }
        $this->registration_number = $this->generateRegistrationNumber($value);
    }

    public function updatedBranches($value, $key): void
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        $index = (int) $parts[0];
        $field = $parts[1];

        if ($field === 'name') {
            if (empty($this->branches[$index]['slug'])) {
                $this->branches[$index]['slug'] = str($value)->slug()->toString();
            }
            if (empty($this->branches[$index]['code'])) {
                $this->branches[$index]['code'] = strtoupper(substr(str($value)->slug('')->toString(), 0, 3)) . rand(100, 999);
            }
        }
    }

    public function removeLogo(): void
    {
        $this->company_logo = null;
        $this->existing_logo_path = null;
    }

    public function removeAttachment(int $attachmentId): void
    {
        $attachment = $this->company->attachments()->whereKey($attachmentId)->first();
        if (!$attachment instanceof Attachment) return;
        Storage::disk($attachment->disk)->delete($attachment->path);
        $attachment->delete();
    }

    public function downloadAttachment(int $attachmentId)
    {
        $attachment = $this->company->attachments()->whereKey($attachmentId)->firstOrFail();
        return Storage::disk($attachment->disk)->download($attachment->path, $attachment->original_name);
    }

    protected function rules(): array
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'company_logo' => [($this->existing_logo_path ? 'nullable' : 'required'), 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:2048'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('companies', 'slug')->ignore($this->companyId), 'alpha_dash'],
            'company_type' => ['required', Rule::in(['TMC', 'Corporate'])],
            'registration_number' => ['required', 'string', 'max:50', Rule::unique('companies', 'registration_number')->ignore($this->companyId)],
            'founded_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'parent_id' => ['nullable', 'integer', 'exists:companies,id', 'different:companyId'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->create_branch) {
            foreach ($this->branches as $index => $branch) {
                $branchId = $branch['id'] ?? null;
                $rules["branches.{$index}.name"] = ['required', 'string', 'max:255'];
                $rules["branches.{$index}.code"] = ['required', 'string', 'max:50', Rule::unique('branches', 'code')->ignore($branchId)];
                $rules["branches.{$index}.slug"] = ['required', 'string', 'max:255', Rule::unique('branches', 'slug')->ignore($branchId)];
                $rules["branches.{$index}.email"] = ['required', 'email', 'max:255'];
                $rules["branches.{$index}.phone"] = ['required', 'string', 'max:50'];
                $rules["branches.{$index}.address_line_1"] = ['required', 'string', 'max:255'];
                $rules["branches.{$index}.city"] = ['required', 'string', 'max:255'];
                $rules["branches.{$index}.state"] = ['required', 'string', 'max:255'];
                $rules["branches.{$index}.country"] = ['required', 'string', 'max:255'];
                $rules["branches.{$index}.latitude"] = ['numeric'];
                $rules["branches.{$index}.longitude"] = ['numeric'];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Please provide a valid name for your company.',
            'company_name.min' => 'Company name must be at least 3 characters.',
            'slug.required' => 'A unique ID/Slug is required for the system.',
            'slug.unique' => 'This ID is already taken by another company.',
            'slug.alpha_dash' => 'The ID can only contain letters, numbers, dashes and underscores.',
            'company_logo.required' => 'A company logo is required.',
            'company_logo.image' => 'The logo must be an image file.',
            'company_logo.max' => 'The logo size must not exceed 2MB.',
            'founded_year.integer' => 'Please enter a valid year (e.g., 2024).',
            'founded_year.max' => 'The founded year cannot be in the future.',
            'status.required' => 'You must select a status for the company.',
            'branches.*.name.required' => 'Branch name is required.',
            'branches.*.code.required' => 'Branch code is required.',
            'branches.*.code.unique' => 'This branch code is already in use.',
            'branches.*.email.required' => 'Branch email is required.',
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
                'founded_year' => $validated['founded_year'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'parent_id' => $validated['parent_id'],
                'settings' => array_merge($this->company->settings ?? [], [
                    'logo_path' => $logoPath,
                ]),
            ]);

            if ($this->company_logo instanceof UploadedFile && $logoPath) {
                $this->company->attachments()->create([
                    'disk' => 'public',
                    'path' => $logoPath,
                    'original_name' => $this->company_logo->getClientOriginalName(),
                    'mime_type' => $this->company_logo->getClientMimeType(),
                    'size' => $this->company_logo->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            foreach ($this->attachments as $attachment) {
                if (!$attachment instanceof UploadedFile) continue;
                $path = $attachment->storePublicly('company-attachments', 'public');
                $this->company->attachments()->create([
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $attachment->getClientOriginalName(),
                    'mime_type' => $attachment->getClientMimeType(),
                    'size' => $attachment->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);
            }

            // Sync Branches
            if ($this->create_branch) {
                foreach ($this->branches as $index => $branchData) {
                    $id = $branchData['id'] ?? null;
                    $this->company->branches()->updateOrCreate(
                        ['id' => $id],
                        array_merge($branchData, [
                            'company_id' => $this->company->id,
                            'is_main' => ($index === 0),
                            'status' => 'active',
                        ])
                    );
                }

                if (!empty($this->branches_to_delete)) {
                    $this->company->branches()->whereIn('id', $this->branches_to_delete)->delete();
                }
            }
        });

        session()->flash('status', 'Company and branches updated successfully.');
        return redirect()->route('companies.index');
    }

    public function render()
    {
        $companies = collect();
        if (auth()->user()->can('Manage Global System')) {
            $descendantIds = $this->getDescendantIds($this->company);
            $excludeIds = array_merge([$this->companyId], $descendantIds);
            $companies = Company::whereNotIn('id', $excludeIds)->orderBy('name')->get(['id', 'name']);
        }

        return view('livewire.company.edit', [
            'companies' => $companies,
            'uploadedAttachments' => $this->company->attachments()->latest()->get(),
        ]);
    }

    private function getDescendantIds($company): array
    {
        $ids = [];
        foreach ($company->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }

    private function generateRegistrationNumber(?string $companyName): string
    {
        $base = Str::of((string) $companyName)->trim()->slug('-')->toString();
        if ($base === '') $base = 'organization';
        $base = Str::limit($base, 45, '');
        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidate = "{$base}-{$suffix}";
        } while (Company::where('registration_number', $candidate)->where('id', '!=', $this->companyId)->exists());
        return $candidate;
    }
}
