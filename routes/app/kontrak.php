<?php

use App\Livewire\Kontrak\Create;
use App\Livewire\Kontrak\Index;
use App\Livewire\Kontrak\Show;
use App\Models\Contract;
use Illuminate\Support\Facades\Route;

Route::prefix('kontrak')->name('kontrak.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Contract::all()->map(fn($c) => [
            'nomor' => $c->nomor,
            'status' => '<span class="bg-' . $c->status_color . '-600 text-' . $c->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $c->status_text .
                '</span>',
            'action' => '<a href="' . route('kontrak.show', $c->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{contract}/json', function (Contract $contract) {
        $data = $contract->items->map(fn($c) => [
            'item' => $c->item->name,
            'qty' => (int) $c->qty . ' ' . $c->item->unit,
            'action' => '<a href="' . route('kontrak.show', $contract->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/create', Create::class)->name('create');
    Route::get('/{contract}', Show::class)->name('show');

});
