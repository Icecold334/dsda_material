<?php

use App\Livewire\Kontrak\Index;
use App\Models\Contract;
use Illuminate\Support\Facades\Route;




// Route::middleware('auth')->group(function () {
Route::get('/kontrak', Index::class)->name('kontrak.index');
Route::get('/kontrak/json', function () {
    return response()->json(
        ['status' => 'success',
        'data'=> Contract::all()->map(fn ($c) => [
            'nomor' => $c->nomor,
            'action' => '<span class="bg-brand-softer text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded">Brand</span>',
        ])]
    );
})->name('kontrak.json');

// });
