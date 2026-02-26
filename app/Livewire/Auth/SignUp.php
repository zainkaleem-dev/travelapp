<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Component;

class SignUp extends Component
{
    #[Rule('required|string|min:2|max:100')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|string|min:8')]
    public string $password = '';

    #[Rule('required|same:password')]
    public string $password_confirmation = '';

    public bool $agreed = false;

    public bool $showPassword = false;
    public bool $showPasswordConfirmation = false;

    public function togglePassword(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function togglePasswordConfirmation(): void
    {
        $this->showPasswordConfirmation = ! $this->showPasswordConfirmation;
    }

    public function register(): void
    {
        $this->validate();

        if (! $this->agreed) {
            $this->addError('agreed', 'You must agree to the Terms of Service.');
            return;
        }

        $user = \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        auth()->login($user);

        session()->flash('success', 'Account created successfully! Welcome aboard.');

        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.sign-up')
            ->layout('layouts.app', ['title' => 'Sign Up - FlightBook']);
    }
}
