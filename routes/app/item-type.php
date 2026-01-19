<?php

use App\Livewire\ItemType\Index;
use App\Livewire\ItemType\Show;
use App\Models\ItemType;
use Illuminate\Support\Facades\Route;

Route::prefix('item-type')->name('item-type.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = ItemType::all()->map(fn($d) => [
            'name' => $d->name,
            'active' => $d->active ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>' : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Tidak Aktif</span>',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('item-type.show', $d->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-item-type-' . $d->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteItemType\', eventData: { itemTypeId: \'' . $d->id . '\' }, title: \'Hapus Tipe Barang?\', text: \'Tipe Barang ' . addslashes($d->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{itemType}', Show::class)->name('show');

});
