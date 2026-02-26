<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Root URL
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
})->name('root');

// Home (after login)
Route::get('/home', \App\Livewire\Pages\Home\Index::class)
    ->middleware('auth')
    ->name('home');

// Login page (Livewire full-page component)
Route::get('/login', \App\Livewire\Auth\Login::class)
    ->middleware('guest')
    ->name('login');

// Signup page (Livewire full-page component)
Route::get('/signup', \App\Livewire\Auth\SignUp::class)
    ->middleware('guest')
    ->name('signup');

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Backward compatibility: old register URL -> signup
Route::redirect('/register', '/signup')->name('register');
Route::get('/forgot-password',  fn() => 'Forgot password page')->name('password.request');
Route::get('/privacy',          fn() => 'Privacy policy')->name('privacy');
Route::get('/terms',            fn() => 'Terms of service')->name('terms');

// Social auth (requires Laravel Socialite)
Route::get('/auth/google',   fn() => 'Google OAuth redirect')->name('auth.google');
Route::get('/auth/facebook', fn() => 'Facebook OAuth redirect')->name('auth.facebook');

// After login redirect
Route::get('/flights', \App\Livewire\Pages\Flights\ListingOneway::class)
    ->middleware('auth')
    ->name('flights.search');
