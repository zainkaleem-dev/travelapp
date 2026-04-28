<?php

namespace App\Livewire\Admin;

use App\Models\TripPurpose as TripPurposeModel;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TripPurpose extends Component
{
    public ?string $trip_type = null;

    public ?string $saveMessage = null;
    public ?string $crudMessage = null;

    public ?int $editingPurposeId = null;
    public bool $showPurposeForm = false;
    public string $purpose_key = '';
    public string $purpose_label = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $row = UserSetting::query()->first();
        $this->trip_type = session('trip_type', $row?->trip_type);
    }

    protected function rules(): array
    {
        $allowedTripTypes = array_keys(UserSetting::tripTypeOptions());

        return [
            'trip_type' => ['nullable', Rule::in($allowedTripTypes)],
        ];
    }

    protected function purposeRules(): array
    {
        return [
            'purpose_key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('trip_purposes', 'key')->ignore($this->editingPurposeId),
            ],
            'purpose_label' => ['required', 'string', 'max:100'],
        ];
    }

    public function saveSettings(): void
    {
        $this->saveMessage = null;
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $settings = UserSetting::query()->firstOrNew();
        $settings->trip_type = $this->trip_type;
        $settings->save();
        session()->put('trip_type', $this->trip_type);

        $this->saveMessage = 'Settings saved.';
        $this->dispatch('user-settings-updated');
    }

    public function savePurpose(): void
    {
        if (!Schema::hasTable('trip_purposes')) {
            $this->crudMessage = 'Trip purpose table is missing. Please run migrations.';
            return;
        }

        $this->crudMessage = null;
        $this->purpose_key = Str::of($this->purpose_key)->lower()->replace('-', '_')->trim()->value();
        $this->validate($this->purposeRules());

        $oldKey = null;
        $purpose = null;

        if ($this->editingPurposeId) {
            $purpose = TripPurposeModel::query()->findOrFail($this->editingPurposeId);
            $oldKey = $purpose->key;
            $purpose->update([
                'key' => $this->purpose_key,
                'label' => $this->purpose_label,
            ]);
            $this->crudMessage = 'Trip purpose updated.';
        } else {
            $purpose = TripPurposeModel::query()->create([
                'key' => $this->purpose_key,
                'label' => $this->purpose_label,
            ]);
            $this->crudMessage = 'Trip purpose created.';
        }

        if ($oldKey !== null && $oldKey !== $purpose->key) {
            $settings = UserSetting::query()->first();
            if ($settings && $settings->trip_type === $oldKey) {
                $settings->update(['trip_type' => $purpose->key]);
                session()->put('trip_type', $purpose->key);
                $this->trip_type = $purpose->key;
                $this->dispatch('user-settings-updated');
            }
        }

        $this->resetPurposeForm();
        $this->showPurposeForm = false;
    }

    public function editPurpose(int $purposeId): void
    {
        if (!Schema::hasTable('trip_purposes')) {
            $this->crudMessage = 'Trip purpose table is missing. Please run migrations.';
            return;
        }

        $purpose = TripPurposeModel::query()->findOrFail($purposeId);

        $this->editingPurposeId = $purpose->id;
        $this->purpose_key = $purpose->key;
        $this->purpose_label = $purpose->label;
        $this->showPurposeForm = true;
        $this->viewingPurposeId = null;
        $this->crudMessage = null;
    }

    public function cancelEdit(): void
    {
        $this->resetPurposeForm();
        $this->showPurposeForm = false;
    }

    public function openCreateForm(): void
    {
        $this->resetPurposeForm();
        $this->showPurposeForm = true;
    }

    public function deletePurpose(int $purposeId): void
    {
        if (!Schema::hasTable('trip_purposes')) {
            $this->crudMessage = 'Trip purpose table is missing. Please run migrations.';
            return;
        }

        $purpose = TripPurposeModel::query()->findOrFail($purposeId);
        $deletedKey = $purpose->key;
        $purpose->delete();

        if ($this->editingPurposeId === $purposeId) {
            $this->resetPurposeForm();
        }

        $settings = UserSetting::query()->first();
        if ($settings && $settings->trip_type === $deletedKey) {
            $settings->update(['trip_type' => null]);
            session()->forget('trip_type');
            $this->trip_type = null;
            $this->dispatch('user-settings-updated');
        }

        $this->crudMessage = 'Trip purpose deleted.';
    }

    private function resetPurposeForm(): void
    {
        $this->editingPurposeId = null;
        $this->purpose_key = '';
        $this->purpose_label = '';
    }

    public function render()
    {
        $hasTripPurposeTable = Schema::hasTable('trip_purposes');

        return view('livewire.admin.trip-purpose', [
            'tripPurposeOptions' => UserSetting::tripTypeOptions(),
            'tripPurposes' => $hasTripPurposeTable
                ? TripPurposeModel::query()->orderBy('id')->get()
                : collect(),
            'hasTripPurposeTable' => $hasTripPurposeTable,
        ]);
    }
}

