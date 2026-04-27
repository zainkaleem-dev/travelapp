<?php

namespace App\Livewire\Company;

use App\Models\Branch;
use App\Models\Attachment;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    // Branch Section (Dynamic Repeater)
    public bool $create_branch = true;
    /** @var array<int, array<string, mixed>> */
    public array $branches = [];

    public function mount(int $id): void
    {
        $this->companyId = $id;

        // 1. Resolve hierarchy for current admin
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->hasRole('Super Admin');
        /** @var \App\Support\TenantContext $tenantContext */
        $tenantContext = app(\App\Support\TenantContext::class);
        $manageableHierarchy = $tenantContext->getManageableHierarchy($currentUser);

        // 2. Resolve the company WITHOUT restrictive global scopes for existence check
        // while maintaining security via explicit hierarchy validation.
        $this->company = Company::query()
            ->withoutGlobalScopes()
            ->findOrFail($id);

        // 3. Security Check: Ensure the target company is within the admin's management hierarchy
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

        $this->loadBranches();
    }

    private function loadBranches(): void
    {
        $this->branches = Branch::query()
            ->withoutGlobalScopes()
            ->where('company_id', $this->company->id)
            ->orderByDesc('is_main')
            ->orderBy('id')
            ->get()
            ->map(fn (Branch $branch) => [
                'id' => $branch->id,
                'name' => $branch->name ?? '',
                'code' => $branch->code ?? '',
                'slug' => $branch->slug ?? '',
                'email' => $branch->email ?? '',
                'phone' => $branch->phone ?? '',
                'address_line_1' => $branch->address_line_1 ?? '',
                'city' => $branch->city ?? '',
                'state' => $branch->state ?? '',
                'country' => $branch->country ?? '',
                'latitude' => (string) ($branch->latitude ?? '0'),
                'longitude' => (string) ($branch->longitude ?? '0'),
            ])
            ->all();

        if ($this->branches === []) {
            $this->addBranch();
        }
    }

    public function addBranch(): void
    {
        $this->branches[] = [
            'id' => null,
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
        if (!isset($this->branches[$index])) {
            return;
        }

        if (count($this->branches) <= 1) {
            return;
        }

        $branchId = $this->branches[$index]['id'] ?? null;
        if ($branchId) {
            Branch::query()
                ->withoutGlobalScopes()
                ->where('company_id', $this->company->id)
                ->whereKey((int) $branchId)
                ->delete();
        }

        unset($this->branches[$index]);
        $this->branches = array_values($this->branches);
    }

    public function updatedBranches($value, $key): void
    {
        $parts = explode('.', (string) $key);
        if (count($parts) < 2) {
            return;
        }

        $index = (int) $parts[0];
        $field = $parts[1];

        if ($field === 'name') {
            $this->updateBranchFields($index, (string) $value);
        }
    }

    private function updateBranchFields(int $index, string $name): void
    {
        if (!isset($this->branches[$index])) {
            return;
        }

        if (empty($this->branches[$index]['slug'])) {
            $this->branches[$index]['slug'] = str($name)->slug()->toString();
        }

        if (empty($this->branches[$index]['code'])) {
            $this->branches[$index]['code'] = strtoupper(substr(str($name)->slug('')->toString(), 0, 3)) . rand(100, 999);
        }
    }

    public function updatedCompanyName($value): void
    {
        if (empty($this->slug)) {
            $this->slug = str($value)->slug()->toString();
        }

        $this->registration_number = $this->generateRegistrationNumber($value);
    }

    public function removeLogo(): void
    {
        $this->company_logo = null;
        $this->existing_logo_path = null;
    }

    public function removeAttachment(int $attachmentId): void
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->first();

        if (!$attachment instanceof Attachment) {
            return;
        }

        Storage::disk($attachment->disk)->delete($attachment->path);
        $attachment->delete();
    }

    public function downloadAttachment(int $attachmentId)
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->firstOrFail();

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_name
        );
    }

    protected function rules(): array
    {
        return [
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

            // Other fields
            'legal_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],

            // Dynamic Branch Validation
            'branches.*.name' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.code' => [Rule::requiredIf($this->create_branch), 'string', 'max:50'],
            'branches.*.slug' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.email' => [Rule::requiredIf($this->create_branch), 'email', 'max:255'],
            'branches.*.phone' => [Rule::requiredIf($this->create_branch), 'string', 'max:50'],
            'branches.*.address_line_1' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.city' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.state' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
            'branches.*.country' => [Rule::requiredIf($this->create_branch), 'string', 'max:255'],
        ];
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
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $this->validateBranchUniqueness();

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

            if ($this->create_branch) {
                foreach ($this->branches as $index => $branchData) {
                    $branchId = $branchData['id'] ?? null;

                    $payload = array_merge($branchData, [
                        'company_id' => $this->company->id,
                        'is_main' => ($index === 0),
                        'status' => 'active',
                    ]);
                    unset($payload['id']);

                    if ($branchId) {
                        Branch::query()
                            ->withoutGlobalScopes()
                            ->where('company_id', $this->company->id)
                            ->whereKey((int) $branchId)
                            ->update($payload);
                    } else {
                        $created = Branch::query()->create($payload);
                        $this->branches[$index]['id'] = $created->id;
                    }
                }

                $mainId = $this->branches[0]['id'] ?? null;
                if ($mainId) {
                    Branch::query()
                        ->withoutGlobalScopes()
                        ->where('company_id', $this->company->id)
                        ->whereKeyNot($mainId)
                        ->update(['is_main' => false]);
                }
            }

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
                if (!$attachment instanceof UploadedFile) {
                    continue;
                }

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

        });

        session()->flash('status', 'Company updated successfully.');
        return redirect()->route('companies.index');
    }

    public function render()
    {
        $companies = collect();

        if (auth()->user()->can('Manage Global System')) {
            // Recursively get all descendant IDs to exclude from the parent list to prevent cycles
            $descendantIds = $this->getDescendantIds($this->company);
            $excludeIds = array_merge([$this->companyId], $descendantIds);

            $companies = Company::whereNotIn('id', $excludeIds)
                ->orderBy('name')
                ->get(['id', 'name']);
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
        if ($base === '') {
            $base = 'organization';
        }

        $base = Str::limit($base, 45, '');

        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidate = "{$base}-{$suffix}";
        } while (
            Company::where('registration_number', $candidate)
                ->where('id', '!=', $this->companyId)
                ->exists()
        );

        return $candidate;
    }

    private function validateBranchUniqueness(): void
    {
        if (!$this->create_branch) {
            return;
        }

        foreach ($this->branches as $index => $branch) {
            $branchId = $branch['id'] ?? null;
            $code = (string) ($branch['code'] ?? '');
            $slug = (string) ($branch['slug'] ?? '');

            if ($code !== '') {
                $query = Branch::query()
                    ->withoutGlobalScopes()
                    ->where('code', $code);
                if ($branchId) {
                    $query->where('id', '!=', (int) $branchId);
                }
                if ($query->exists()) {
                    throw ValidationException::withMessages([
                        "branches.{$index}.code" => 'This branch code is already in use.',
                    ]);
                }
            }

            if ($slug !== '') {
                $query = Branch::query()
                    ->withoutGlobalScopes()
                    ->where('slug', $slug);
                if ($branchId) {
                    $query->where('id', '!=', (int) $branchId);
                }
                if ($query->exists()) {
                    throw ValidationException::withMessages([
                        "branches.{$index}.slug" => 'This branch slug is already in use.',
                    ]);
                }
            }
        }
    }
}
