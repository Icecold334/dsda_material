<?php


use App\Models\Rab;
use App\Livewire\Rab\Show;
use App\Livewire\Rab\Index;
use Illuminate\Support\Facades\Route;


Route::prefix('rab')->name('rab.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Rab::all()->map(fn($r) => [
            'nomor' => $r->nomor,
            'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $r->status_text .
                '</span>',
            'action' => '<a href="' . route('rab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{rab}/json', function (Rab $rab) {
        $data = $rab->items->map(fn($r) => [
            'item' => $r->item->name,
            'qty' => (int) $r->qty . ' ' . $r->item->unit,
            'action' => '<a href="' . route('rab.show', $rab->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{rab}', Show::class)->name('show');

});