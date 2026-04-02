<?php

namespace App\Livewire\Tmc;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class TmcSettings extends Component
{
    public function render()
    {
        return view('Livewire.tmc.tmc-settings');
    }
}

