<?php

use App\Livewire\Driver\Index;
use App\Livewire\Driver\Show;
use App\Models\Personnel;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')->name('driver.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Personnel::where('type', 'driver')->with('sudin')->get()->map(fn($d) => [
            'name' => $d->name,
            'sudin' => $d->sudin?->name ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('driver.show', $d->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-driver-' . $d->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteDriver\', eventData: { driverId: \'' . $d->id . '\' }, title: \'Hapus Driver?\', text: \'Driver ' . addslashes($d->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{driver}', Show::class)->name('show');

});
