<?php

use App\Livewire\Item\Index;
use App\Livewire\Item\Show;
use App\Models\Item;
use Illuminate\Support\Facades\Route;

Route::prefix('item')->name('item.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Item::with(['sudin', 'category'])->get()->map(fn($i) => [
            'name' => $i->name,
            'category' => $i->category?->name ?? '-',
            'sudin' => $i->sudin?->name ?? '-',
            'unit' => $i->unit ?? '-',
            'status' => $i->active
                ? '<span class="bg-success-600 text-white text-xs font-medium px-2 py-0.5 rounded">Aktif</span>'
                : '<span class="bg-danger-600 text-white text-xs font-medium px-2 py-0.5 rounded">Nonaktif</span>',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('item.show', $i->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-item-' . $i->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteItem\', eventData: { itemId: \'' . $i->id . '\' }, title: \'Hapus Barang?\', text: \'Barang ' . addslashes($i->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{item}', Show::class)->name('show');

});
