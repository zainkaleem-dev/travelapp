<?php

namespace App\Livewire\Admin;

use App\Models\TripPurpose;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TripPurposeView extends Component
{
    public TripPurpose $tripPurpose;

    public function mount(TripPurpose $tripPurpose): void
    {
        $this->tripPurpose = $tripPurpose;
    }

    public function render()
    {
        return view('livewire.admin.trip-purpose-view');
    }
}

