<div class="card">
    <div class="top">
        <h1>Sign Up</h1>
        <a class="link muted" href="{{ url('/') }}">Home</a>
    </div>

    <div class="muted">Create your account to continue.</div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form wire:submit.prevent="register" style="margin-top: 10px;">
        <label for="name">Name</label>
        <input id="name" type="text" wire:model.defer="name" autocomplete="name" required autofocus>

        <label for="email">Email</label>
        <input id="email" type="email" wire:model.defer="email" autocomplete="email" required>

        <label for="password">Password</label>
        <input id="password" type="password" wire:model.defer="password" autocomplete="new-password" required>

        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" wire:model.defer="password_confirmation" autocomplete="new-password" required>

        <button class="btn" type="submit" wire:loading.attr="disabled" wire:target="register">
            Create Account
        </button>
    </form>

    <div class="muted" style="margin-top: 14px;">
        Already have an account?
        <a class="link" href="{{ route('login') }}">Login</a>
    </div>
</div>
