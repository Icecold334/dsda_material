<?php

use App\Livewire\Security\Index;
use App\Livewire\Security\Show;
use App\Models\Personnel;
use Illuminate\Support\Facades\Route;

Route::prefix('security')->name('security.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Personnel::where('type', 'security')->with('sudin')->get()->map(fn($s) => [
            'name' => $s->name,
            'sudin' => $s->sudin?->name ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('security.show', $s->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-security-' . $s->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteSecurity\', eventData: { securityId: \'' . $s->id . '\' }, title: \'Hapus Security?\', text: \'Security ' . addslashes($s->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{security}', Show::class)->name('show');

});
