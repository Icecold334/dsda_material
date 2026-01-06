<?php

use App\Livewire\Warehouse\Index;
use App\Livewire\Warehouse\Show;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

Route::prefix('warehouse')->name('warehouse.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Warehouse::with('sudin')->get()->map(fn($w) => [
            'name' => $w->name,
            'sudin' => $w->sudin?->name ?? '-',
            'location' => $w->location ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('warehouse.show', $w->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-warehouse-' . $w->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteWarehouse\', eventData: { warehouseId: \'' . $w->id . '\' }, title: \'Hapus Gudang?\', text: \'Gudang ' . addslashes($w->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{warehouse}', Show::class)->name('show');

});
