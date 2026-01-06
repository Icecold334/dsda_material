<?php

use App\Livewire\Driver\Index;
use App\Livewire\Driver\Show;
use App\Models\Driver;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')->name('driver.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Driver::with('sudin')->get()->map(fn($d) => [
            'name' => $d->name,
            'sudin' => $d->sudin?->name ?? '-',
            'action' => '<a href="' . route('driver.show', $d->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{driver}', Show::class)->name('show');

});
