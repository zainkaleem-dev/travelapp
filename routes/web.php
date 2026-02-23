<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Home\Index;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/home', Index::class)->name('home');

