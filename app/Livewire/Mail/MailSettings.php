<?php

namespace App\Livewire\Mail;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.flight')]
class MailSettings extends Component
{
    public function render()
    {
        return view('livewire.mail.mail-settings');
    }
}
