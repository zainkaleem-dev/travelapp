<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Setting extends Component
{
    public function render()
    {
        return view('livewire.settings.settings');
    }
}