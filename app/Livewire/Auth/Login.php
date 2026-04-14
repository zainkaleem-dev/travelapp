<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login extends Component
{
    // ─── Form fields ──────────────────────────────────────────────
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:6')]
    public string $password = '';

    public bool $remember = false;
    public bool $showPassword = false;

    // ─── UI state ─────────────────────────────────────────────────
    #[Locked]
    public bool $isLoading = false;
    public string $errorMessage = '';

    // ─── Booking context (passed from previous step) ───────────────
    public string $flightFrom = 'DUS';
    public string $flightTo = 'IST';
    public int $passengers = 1;
    public string $cabinClass = 'ECO';

    // ─── Toggle password visibility ───────────────────────────────
    public function togglePassword(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    // ─── Rate-limit key ───────────────────────────────────────────
    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    // ─── Login action ─────────────────────────────────────────────
    public function login(): void
    {
        $this->errorMessage = '';

        // Validate inputs
        $this->validate();

        // Rate limiting: 5 attempts per minute
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            $this->errorMessage = "Too many login attempts. Please try again in {$seconds} seconds.";
            return;
        }

        $this->isLoading = true;

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            $this->isLoading = false;
            $this->errorMessage = 'These credentials do not match our records.';
            $this->reset('password');
            return;
        }

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->isLoading = false;
            $this->errorMessage = 'Please verify your email first. A new verification link has been sent.';
            return;
        }

        // Clear rate limiter on success
        RateLimiter::clear($this->throttleKey());

        Auth::login($user, $this->remember); 
        request()->session()->regenerate(); 

        if ($user->hasRole('super_admin')) {
            $this->redirect(route('superadmin.companies.index'));
            return;
        }

        $this->redirect(route('flights.search'));
    } 

    // ─── Social auth placeholders ─────────────────────────────────
    public function loginWithGoogle(): void
    {
        // Integrate with Laravel Socialite:
        // return redirect(Socialite::driver('google')->redirect());
        $this->redirect('/auth/google');
    }

    public function loginWithFacebook(): void
    {
        // return redirect(Socialite::driver('facebook')->redirect());
        $this->redirect('/auth/facebook');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.app', ['title' => 'Login – FlightBook']);
    }
}
