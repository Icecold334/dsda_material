<?php

use App\Livewire\District\Index;
use App\Livewire\District\Show;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Route;

Route::prefix('district')->name('district.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = District::with('sudin')->get()->map(fn($d) => [
            'name' => $d->name,
            'sudin' => $d->sudin?->name ?? '-',
            'action' => '
                <div class="flex gap-1">
                    <a href="' . route('district.show', $d->id) . '" class="bg-blue-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-district-' . $d->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteDistrict\', eventData: { districtId: \'' . $d->id . '\' }, title: \'Hapus District?\', text: \'District ' . addslashes($d->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{district}/json', function (District $district) {
        $data = $district->subdistricts->map(fn($s) => [
            'name' => $s->name,
            'action' => '
                <div class="flex gap-1">
                    <button onclick="window.Livewire.dispatch(\'open-modal\', \'edit-subdistrict-' . $s->id . '\')" class="bg-warning-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Edit</button>
                    <button onclick="SwalConfirm.delete({ eventName: \'deleteSubdistrict\', eventData: { subdistrictId: \'' . $s->id . '\' }, title: \'Hapus Subdistrict?\', text: \'Subdistrict ' . addslashes($s->name) . ' akan dihapus!\' })" class="bg-red-600 text-white text-xs font-medium px-1.5 py-0.5 rounded">Hapus</button>
                </div>
            ',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{district}', Show::class)->name('show');

});