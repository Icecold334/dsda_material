<?php


use App\Models\Delivery;
use App\Livewire\Pengiriman\Show;
use App\Livewire\Pengiriman\Index;
use Illuminate\Support\Facades\Route;


Route::prefix('pengiriman')->name('pengiriman.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Delivery::all()->map(fn($r) => [
            'nomor' => $r->nomor,
            'gudang' => $r->warehouse->name,
            'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $r->status_text .
                '</span>',
            'action' => '<a href="' . route('pengiriman.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{pengiriman}/json', function (Delivery $pengiriman) {
        $data = $pengiriman->items->map(fn($r) => [
            'kode' => $r->item->code,
            'item' => $r->item->name,
            'qty' => (int) $r->qty . ' ' . $r->item->unit,
            'action' => '<a href="' . route('pengiriman.show', $pengiriman->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{pengiriman}', Show::class)->name('show');

});