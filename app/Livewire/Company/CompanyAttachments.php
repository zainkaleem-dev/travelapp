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

    public function renameAndDownloadAttachment(int $attachmentId)
    {
        $attachment = $this->company
            ->attachments()
            ->whereKey($attachmentId)
            ->firstOrFail();

        $requestedName = trim((string) ($this->downloadNames[$attachmentId] ?? ''));
        $requestedName = preg_replace('/[\\\\\/:*?"<>|]+/', '', $requestedName) ?? '';

        if ($requestedName === '') {
            $this->addError("downloadNames.{$attachmentId}", 'Please enter a valid file name.');
            return null;
        }

        $currentExtension = pathinfo($attachment->original_name, PATHINFO_EXTENSION);
        if ($currentExtension === '') {
            $currentExtension = pathinfo($attachment->path, PATHINFO_EXTENSION);
        }

        $requestedBase = pathinfo($requestedName, PATHINFO_FILENAME);
        $requestedExtension = pathinfo($requestedName, PATHINFO_EXTENSION);

        $finalDownloadName = $requestedName;
        if ($currentExtension !== '' && strtolower($requestedExtension) !== strtolower($currentExtension)) {
            $finalDownloadName = ($requestedBase !== '' ? $requestedBase : 'attachment') . '.' . $currentExtension;
        }

        $safeStorageBase = Str::slug(pathinfo($finalDownloadName, PATHINFO_FILENAME), '-');
        if ($safeStorageBase === '') {
            $safeStorageBase = 'attachment';
        }

        $directory = pathinfo($attachment->path, PATHINFO_DIRNAME);
        $directory = ($directory === '.' || $directory === '\\') ? '' : trim((string) $directory, '/\\');
        $extensionSuffix = $currentExtension !== '' ? '.' . $currentExtension : '';

        $disk = Storage::disk($attachment->disk);
        $newPath = ($directory !== '' ? $directory . '/' : '') . $safeStorageBase . $extensionSuffix;

        if ($newPath !== $attachment->path) {
            $candidatePath = $newPath;
            $counter = 1;

            while ($disk->exists($candidatePath)) {
                $candidatePath = ($directory !== '' ? $directory . '/' : '') . $safeStorageBase . '-' . $counter . $extensionSuffix;
                $counter++;
            }

            if ($disk->exists($attachment->path)) {
                $disk->move($attachment->path, $candidatePath);
                $newPath = $candidatePath;
            }
        }

        $attachment->update([
            'path' => $newPath,
            'original_name' => $finalDownloadName,
        ]);

        $this->downloadNames[$attachmentId] = $finalDownloadName;

        return $disk->download(
            $attachment->path,
            $attachment->original_name
        );
    }

    public function render()
    {
        return view('livewire.company.attachments', [
            'uploadedAttachments' => $this->company->attachments()->latest()->get(),
        ]);
    }
}

