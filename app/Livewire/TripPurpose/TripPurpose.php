<?php

namespace App\Livewire\TripPurpose;

use App\Models\TripPurpose as TripPurposeModel;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TripPurpose extends Component
{
    public ?string $trip_type = null;

    public ?string $saveMessage = null;
    public ?string $crudMessage = null;

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

    public function deletePurpose(int $purposeId): void
    {
        if (!Schema::hasTable('trip_purposes')) {
            $this->crudMessage = 'Trip purpose table is missing. Please run migrations.';
            return;
        }

        $purpose = TripPurposeModel::query()->findOrFail($purposeId);
        $deletedKey = $purpose->key;
        $purpose->delete();

        $settings = UserSetting::query()->first();
        if ($settings && $settings->trip_type === $deletedKey) {
            $settings->update(['trip_type' => null]);
            session()->forget('trip_type');
            $this->trip_type = null;
            $this->dispatch('user-settings-updated');
        }

        $this->crudMessage = 'Trip purpose deleted.';
    }

    public function render()
    {
        $hasTripPurposeTable = Schema::hasTable('trip_purposes');

        return view('livewire.trip-purpose.index', [
            'tripPurposeOptions' => UserSetting::tripTypeOptions(),
            'tripPurposes' => $hasTripPurposeTable
                ? TripPurposeModel::query()->orderBy('id')->get()
                : collect(),
            'hasTripPurposeTable' => $hasTripPurposeTable,
        ]);
    }
}
