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
    use WithFileUploads, HandlesCompanyAttachments;

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
    public array $attachmentNames = []; // Map of index => custom name for NEW attachments
    public array $existingAttachmentNames = []; // Map of ID => custom name for EXISTING attachments
    public ?string $existing_logo_path = null;
    public ?int $founded_year = null;
    public ?string $description = null;
    public ?int $parent_id = null;

    // SaaS / Status
    public string $status = 'active';
    public ?string $notes = null;
    public string $foreground_color = '#000000';
    public string $background_color = '#ffffff';


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
        $this->foreground_color = $this->company->settings['foreground_color'] ?? '#000000';
        $this->background_color = $this->company->settings['background_color'] ?? '#ffffff';

        $this->existingAttachmentNames = $this->company
            ->attachments()
            ->pluck('original_name', 'id')
            ->toArray();
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
        unset($this->existingAttachmentNames[$attachmentId]);
    }

    public function removeNewAttachment(int $index): void
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            unset($this->attachmentNames[$index]);
            $this->attachments = array_values($this->attachments);
            $this->attachmentNames = array_values($this->attachmentNames);
        }
    }

    public function renameAttachment(int $attachmentId, string $newName): void
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->firstOrFail();

        $result = $this->renameAttachmentPhysically($attachment, $newName);
        
        if (isset($result['error']) && !$result['success']) {
            $this->addError("attachments.{$attachmentId}", $result['error']);
            return;
        }

        $attachment->update([
            'path' => $result['path'],
            'original_name' => $result['original_name']
        ]);
        $this->existingAttachmentNames[$attachmentId] = $result['original_name'];
    }

    public function renameNewAttachment(int $index, string $newName): void
    {
        if (isset($this->attachments[$index])) {
            $this->attachmentNames[$index] = $newName;
        }
    }

    public function downloadAttachment(int $attachmentId)
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->firstOrFail();

        $disk = Storage::disk($attachment->disk);

        if (!$disk->exists($attachment->path)) {
            session()->flash('error', "Unable to retrieve the file_size for file at location: {$attachment->path}.");
            return null;
        }

        return $disk->download(
            $attachment->path,
            $attachment->original_name
        );
    }

    public function downloadNewAttachment(int $index)
    {
        if (isset($this->attachments[$index])) {
            return response()->download($this->attachments[$index]->getRealPath(), $this->attachmentNames[$index] ?? $this->attachments[$index]->getClientOriginalName());
        }
    }

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', 'min:3'],
            'company_logo' => [($this->existing_logo_path ? 'nullable' : 'required'), 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:20480'],
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
                    'foreground_color' => $this->foreground_color,
                    'background_color' => $this->background_color,
                ]),
            ]);


            foreach ($this->attachments as $index => $attachment) {
                if (!$attachment instanceof UploadedFile) {
                    continue;
                }

                $customName = $this->attachmentNames[$index] ?? $attachment->getClientOriginalName();
                $stored = $this->storeAttachmentPhysically($attachment, $customName);
                $this->company->attachments()->create([
                    'disk' => 'public',
                    'path' => $stored['path'],
                    'original_name' => $stored['original_name'],
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

}
