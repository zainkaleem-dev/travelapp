<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CompanyAttachments extends Component
{
    use HandlesCompanyAttachments;
    public int $companyId;
    public Company $company;
    public array $downloadNames = [];

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

        if (!$isSuperAdmin && !in_array($this->company->id, $manageableHierarchy, true)) {
            abort(403, 'You do not have permission to view this organization (Access denied).');
        }

        $this->downloadNames = $this->company
            ->attachments()
            ->pluck('original_name', 'id')
            ->toArray();
    }

    public function renameAttachment(int $attachmentId): void
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->firstOrFail();

        $requestedName = trim((string) ($this->downloadNames[$attachmentId] ?? ''));
        $requestedName = preg_replace('/[\\\\\/:*?"<>|]+/', '', $requestedName) ?? '';

        if ($requestedName === '') {
            $this->addError("downloadNames.{$attachmentId}", 'Please enter a valid file name.');
            return;
        }
        
        $result = $this->renameAttachmentPhysically($attachment, $requestedName);
        
        if (isset($result['error']) && !$result['success']) {
            $this->addError("downloadNames.{$attachmentId}", $result['error']);
            return;
        }

        $attachment->update([
            'path' => $result['path'],
            'original_name' => $result['original_name'],
        ]);

        $this->downloadNames[$attachmentId] = $result['original_name'];
        session()->flash('status', 'Attachment renamed successfully.');
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

        return $disk->download($attachment->path, $attachment->original_name);
    }

    public function render()
    {
        return view('livewire.company.attachments', [
            'uploadedAttachments' => $this->company->attachments()->latest()->get(),
        ]);
    }
}

