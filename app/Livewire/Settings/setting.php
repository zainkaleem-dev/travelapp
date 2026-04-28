<?php

namespace App\Livewire\Settings;

use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Setting extends Component
{
    /** @var string|null Stored key: business_trip | personal_trip | annual_trip | guest */
    public ?string $trip_type = null;

    public ?string $saveMessage = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $row = UserSetting::query()->where('user_id', $user->id)->first();
        $this->trip_type = session('trip_type', $row?->trip_type);
    }

    protected function rules(): array
    {
        return [
            'trip_type' => 'nullable|in:business_trip,personal_trip,annual_trip,guest',
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

        $settings = UserSetting::query()->firstOrNew(['user_id' => $user->id]);
        $settings->trip_type = $this->trip_type;
        $settings->save();
        session()->put('trip_type', $this->trip_type);

        $this->saveMessage = 'Settings saved.';
        $this->dispatch('user-settings-updated');
    }

    public function render()
    {
        return view('livewire.settings.settings');
    }
}
