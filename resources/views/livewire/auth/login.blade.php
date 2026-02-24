<div class="card">
    <div class="top">
        <h1>Login</h1>
        <!-- <a class="link muted" href="{{ url('/') }}">Home</a> -->
    </div>

    <div class="muted">Sign in with your email and password.</div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form wire:submit.prevent="login" style="margin-top: 10px;">
        <label for="email">Email</label>
        <input id="email" type="email" wire:model.defer="email" autocomplete="email" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" wire:model.defer="password" autocomplete="current-password" required>

        <div class="row">
            <label style="margin:0; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" wire:model.defer="remember">
                <span class="muted">Remember me</span>
            </label>

            <div class="muted" wire:loading wire:target="login">Checking...</div>
        </div>

        <button class="btn" type="submit" wire:loading.attr="disabled" wire:target="login">
            Login
        </button>
    </form>

    <div class="muted" style="margin-top: 14px;">
        No account?
        <a class="link" href="{{ route('register') }}">Create one</a>
    </div>
</div>
