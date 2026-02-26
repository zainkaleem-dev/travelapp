# 🔐 Login – Livewire 3 Component

Pixel-perfect conversion of the FlightBook login page to **Laravel 11 + Livewire 3**.

---

## 📁 File Structure

```
app/
└── Livewire/
    └── Login.php                              ← Component class (all logic)

resources/views/
├── layouts/
│   └── app.blade.php                          ← Base HTML layout
└── livewire/
    └── login.blade.php                        ← Blade template (UI)

routes/
└── web.php                                    ← Auth routes
```

---

## 🚀 Quick Setup

```bash
# 1. Install Laravel + Livewire
composer create-project laravel/laravel my-app
cd my-app
composer require livewire/livewire

# 2. Copy the files into their paths above

# 3. Configure your database in .env, then run migrations
php artisan migrate

# 4. Serve
php artisan serve
# Visit: http://localhost:8000/login
```

---

## ⚙️ Livewire 3 Features Used

| Feature | Where used |
|---|---|
| `#[Validate]` attribute | Inline validation rules on `$email` and `$password` |
| `#[Locked]` attribute | Prevents `$isLoading` from being tampered by the client |
| `wire:model` | Two-way binding on email, password, remember checkbox |
| `wire:submit` | Form submission → calls `login()` method |
| `wire:click` | Password toggle, social login buttons |
| `wire:loading` / `wire:loading.attr` | Button spinner + disabled state during login |
| `wire:target` | Scoped loading indicator only for `login` action |
| `$this->validate()` | Server-side validation with `@error` display in Blade |
| `$this->redirect(..., navigate: true)` | SPA-style redirect using Livewire Navigate |
| `RateLimiter` | Brute-force protection (5 attempts / minute per email+IP) |

---

## 🔌 Social Login (Google / Facebook)

Install Laravel Socialite:
```bash
composer require laravel/socialite
```

Then update `Login.php`:
```php
use Laravel\Socialite\Facades\Socialite;

public function loginWithGoogle(): void
{
    $this->redirect(Socialite::driver('google')->redirect()->getTargetUrl());
}
```

Add credentials to `config/services.php` and `.env`:
```env
GOOGLE_CLIENT_ID=your-id
GOOGLE_CLIENT_SECRET=your-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## 🔒 Security Notes

- Passwords are never stored in plain text — Laravel's `Auth::attempt()` handles bcrypt comparison
- Rate limiting is applied: 5 failed attempts per email+IP per minute
- Session is regenerated after successful login to prevent session fixation
- `#[Locked]` on `$isLoading` prevents client-side manipulation
