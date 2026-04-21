<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PasswordSetup extends Component
{
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        if (auth()->user()?->has_set_password || auth()->user()?->hasRole('Super Admin')) {
            $this->redirect(route('root'));
        }
    }

    public function save(): void
    {
        $this->validate([
            'new_password' => ['required', 'string', 'min:8', 'same:new_password_confirmation'],
            'new_password_confirmation' => ['required', 'string'],
        ]);

        $user = auth()->user();
        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        $user->update([
            'password' => $this->new_password,
            'has_set_password' => true,
        ]);

        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        session()->flash('success', 'Password updated successfully. Please login with your new password.');
        $this->redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.auth.password-setup');
    }
}

