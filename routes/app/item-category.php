<?php

use App\Livewire\ItemCategory\Index;
use App\Livewire\ItemCategory\Show;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Route;

Route::prefix('item-category')->name('item-category.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = ItemCategory::with(['unit'])->get()->map(fn($c) => [
            'name' => $c->name,
            'unit' => $c->unit?->name ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('item-category.show', $c->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-item-category-' . $c->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteItemCategory\', eventData: { itemCategoryId: \'' . $c->id . '\' }, title: \'Hapus Barang?\', text: \'Barang ' . addslashes($c->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{itemCategory}/json', function (ItemCategory $itemCategory) {
        $data = $itemCategory->items()->with(['sudin'])->get()->map(fn($i) => [
            'name' => $i->spec,
            'sudin' => $i->sudin?->name ?? '-',
            'status' => $i->active
                ? '<span class="bg-success-600 text-white text-xs font-medium px-2 py-0.5 rounded">Aktif</span>'
                : '<span class="bg-warning-600 text-white text-xs font-medium px-2 py-0.5 rounded">Nonaktif</span>',
            'action' => '
                <div class="flex gap-1">
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-item-' . $i->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteItem\', eventData: { itemId: \'' . $i->id . '\' }, title: \'Hapus Spesifikasi?\', text: \'Spesifikasi ' . addslashes($i->spec) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{itemCategory}', Show::class)->name('show');

});
