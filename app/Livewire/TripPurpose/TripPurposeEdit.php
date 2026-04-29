<?php

namespace App\Livewire\TripPurpose;

use App\Models\TripPurpose;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TripPurposeEdit extends Component
{
    public TripPurpose $tripPurpose;
    public string $purpose_key = '';
    public string $purpose_label = '';

    public function mount(TripPurpose $tripPurpose): void
    {
        $this->tripPurpose = $tripPurpose;
        $this->purpose_key = $tripPurpose->key;
        $this->purpose_label = $tripPurpose->label;
    }

    protected function rules(): array
    {
        return [
            'purpose_key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('trip_purposes', 'key')->ignore($this->tripPurpose->id),
            ],
            'purpose_label' => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $oldKey = $this->tripPurpose->key;
        $this->purpose_key = Str::of($this->purpose_key)->lower()->replace('-', '_')->trim()->value();
        $this->validate();

        $this->tripPurpose->update([
            'key' => $this->purpose_key,
            'label' => $this->purpose_label,
        ]);

        $settings = \App\Models\UserSetting::query()->first();
        if ($settings && $settings->trip_type === $oldKey && $oldKey !== $this->purpose_key) {
            $settings->update(['trip_type' => $this->purpose_key]);
            session()->put('trip_type', $this->purpose_key);
        }

        session()->flash('status', 'Trip updated successfully.');
        $this->redirectRoute('admin.trip-purpose');
    }

    public function render()
    {
        return view('livewire.trip-purpose.edit');
    }
}

