<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Test;
use App\Livewire\TestTable;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', Test::class);
Route::get('/table', TestTable::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Livewire\Profile\Show::class)->name('profile.edit');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
