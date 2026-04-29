<?php

namespace App\Livewire\TripPurpose;

use App\Models\TripPurpose;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TripPurposeCreate extends Component
{
    public string $purpose_key = '';
    public string $purpose_label = '';

    protected function rules(): array
    {
        return [
            'purpose_key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                'unique:trip_purposes,key',
            ],
            'purpose_label' => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->purpose_key = Str::of($this->purpose_key)->lower()->replace('-', '_')->trim()->value();
        $this->validate();

        TripPurpose::create([
            'key' => $this->purpose_key,
            'label' => $this->purpose_label,
        ]);

        session()->flash('status', 'Trip purpose created successfully.');
        $this->redirectRoute('admin.trip-purpose');
    }

    public function render()
    {
        return view('livewire.trip-purpose.create');
    }
}
