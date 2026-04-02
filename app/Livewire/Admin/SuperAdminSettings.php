<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.flight')]
class SuperAdminSettings extends Component
{
    public function render()
    {
        return view('Livewire.admin.super-admin-settings');
    }
}

