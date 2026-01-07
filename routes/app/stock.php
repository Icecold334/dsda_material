<?php

use App\Livewire\Stock\Index;
use App\Livewire\Stock\Show;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Warehouse::with(['sudin', 'stocks'])->withCount('stocks')->get()->map(fn($w) => [
            'sudin' => $w->sudin->name,
            'warehouse' => $w->name,
            'total_items' => $w->stocks_count . ' item',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('stock.show', $w->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{warehouse}/json', function (Warehouse $warehouse) {
        $data = $warehouse->stocks()->with('item.category')->get()->map(fn($s) => [
            'category' => $s->item->category?->name ?? '-',
            'code' => '<span class="font-mono">' . $s->item->code . '</span>',
            'name' => $s->item->name,
            'spec' => $s->item->spec ?? '-',
            'unit' => $s->item->unit ?? '-',
            'qty' => number_format($s->qty, 2),
            'status' => $s->item->active
                ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Aktif</span>'
                : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Tidak Aktif</span>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{warehouse}', Show::class)->name('show');
});
