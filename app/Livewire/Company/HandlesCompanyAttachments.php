<?php

namespace App\Livewire\Company;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesCompanyAttachments
{
    /**
     * Store a new uploaded file with a safe physical name based on the custom name.
     */
    protected function storeAttachmentPhysically(UploadedFile $file, string $customName, string $directory = 'company-attachments', string $disk = 'public'): array
    {
        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
        
        $baseName = pathinfo($customName, PATHINFO_FILENAME);
        $safeStorageBase = Str::slug($baseName, '-');
        if ($safeStorageBase === '') {
            $safeStorageBase = 'attachment';
        }
        
        $extensionSuffix = $extension ? '.' . $extension : '';
        
        $storage = Storage::disk($disk);
        $newPath = $directory . '/' . $safeStorageBase . $extensionSuffix;
        $candidatePath = $newPath;
        $counter = 1;
        
        while ($storage->exists($candidatePath)) {
            $candidatePath = $directory . '/' . $safeStorageBase . '-' . $counter . $extensionSuffix;
            $counter++;
        }
        
        $file->storeAs($directory, basename($candidatePath), $disk);
        
        return [
            'path' => $candidatePath,
            'original_name' => $customName,
        ];
    }

    /**
     * Rename an existing attachment physically on the disk.
     */
    protected function renameAttachmentPhysically($attachment, string $newName): array
    {
        $disk = Storage::disk($attachment->disk);
        
        if (!$disk->exists($attachment->path)) {
            return [
                'path' => $attachment->path,
                'original_name' => $newName,
                'success' => false,
                'error' => 'File not found in storage.'
            ];
        }
        
        $currentExtension = pathinfo($attachment->original_name, PATHINFO_EXTENSION);
        if ($currentExtension === '') {
            $currentExtension = pathinfo($attachment->path, PATHINFO_EXTENSION);
        }
        
        $requestedBase = pathinfo($newName, PATHINFO_FILENAME);
        $requestedExtension = pathinfo($newName, PATHINFO_EXTENSION);
        
        $finalDownloadName = $newName;
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
        
        return [
            'path' => $newPath,
            'original_name' => $finalDownloadName,
            'success' => true,
        ];
    }
}
