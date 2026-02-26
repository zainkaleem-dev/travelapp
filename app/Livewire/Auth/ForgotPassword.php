<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public string $successMessage = '';
    public string $errorMessage = '';

    public function sendResetLink(): void
    {
        $this->reset('successMessage', 'errorMessage');
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->successMessage = __($status);
            return;
        }

        $this->errorMessage = __($status);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('layouts.app', ['title' => 'Forgot Password - FlightBook']);
    }
}

