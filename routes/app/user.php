<?php

use App\Livewire\User\Index;
use App\Livewire\User\Show;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = User::with(['sudin', 'division', 'position'])->get()->map(fn($u) => [
            'name' => $u->name,
            'email' => $u->email,
            'nip' => $u->nip ?? '-',
            'sudin' => $u->sudin?->name ?? '-',
            'division' => $u->division?->name ?? '-',
            'position' => $u->position?->name ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('user.show', $u->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-user-' . $u->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteUser\', eventData: { userId: \'' . $u->id . '\' }, title: \'Hapus Pengguna?\', text: \'Pengguna ' . addslashes($u->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{user}', Show::class)->name('show');

});
