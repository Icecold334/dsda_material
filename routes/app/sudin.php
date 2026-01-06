<?php

use App\Livewire\Sudin\Index;
use App\Livewire\Sudin\Show;
use App\Models\Sudin;
use Illuminate\Support\Facades\Route;

Route::prefix('sudin')->name('sudin.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Sudin::all()->map(fn($s) => [
            'name' => $s->name,
            'short' => $s->short ?? '-',
            'address' => $s->address ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('sudin.show', $s->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-sudin-' . $s->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteSudin\', eventData: { sudinId: \'' . $s->id . '\' }, title: \'Hapus Sudin?\', text: \'Sudin ' . addslashes($s->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{sudin}', Show::class)->name('show');

});