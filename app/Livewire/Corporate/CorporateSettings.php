<?php

namespace App\Livewire\Corporate;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class CorporateSettings extends Component
{
    public function render()
    {
        return view('Livewire.corporate.corporate-settings');
    }
}

