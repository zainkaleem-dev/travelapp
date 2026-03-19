<?php

namespace App\Livewire\Concierge;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class Concierge extends Component
{
    public function render()
    {
        return view('livewire.concierge.concierge');
    }
}

