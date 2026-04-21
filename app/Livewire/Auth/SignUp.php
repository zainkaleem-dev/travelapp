<?php

namespace App\Livewire\Auth;

use App\Models\UserPersonalInfo;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SignUp extends Component
{
    #[Rule('required|string|min:2|max:60')]
    public string $first_name = '';

    #[Rule('nullable|string|min:1|max:60')]
    public string $middle_name = '';

    #[Rule('required|string|min:1|max:60')]
    public string $last_name = '';

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
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordConfirmation(): void
    {
        $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
    }

    public function register(): void
    {
        $this->validate();

        if (!$this->agreed) {
            $this->addError('agreed', 'You must agree to the Terms of Service.');
            return;
        }

        $user = \App\Models\User::create([
            'first_name' => $this->first_name,
            'middle_name' => trim($this->middle_name) !== '' ? $this->middle_name : null,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'has_set_password' => true,
        ]);

        // Keep user_personal_infos in sync for newly created accounts.
        UserPersonalInfo::query()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $user->sendEmailVerificationNotification();

        session()->flash('success', 'Account created. Verification email sent. Please verify your email, then login.');

        $this->redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.auth.sign-up')
            ->layout('layouts.app', ['title' => 'Sign Up - FlightBook']);
    }
}
