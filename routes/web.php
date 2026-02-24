<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home\Index;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');


Route::livewire('/home', Index::class)->name('home');


Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// Route::view('/dashboard', 'dashboard')
//     ->middleware('auth')
//     ->name('dashboard');
