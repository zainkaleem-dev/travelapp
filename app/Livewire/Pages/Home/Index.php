<?php

namespace App\Livewire\Pages\Home;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.home.index')
            ->layout('layouts.flightworld');
    }
}
