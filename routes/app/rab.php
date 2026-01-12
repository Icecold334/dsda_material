<?php


use App\Models\Rab;
use App\Livewire\Rab\Show;
use App\Livewire\Rab\Index;
use Illuminate\Support\Facades\Route;


Route::prefix('rab')->name('rab.')->group(function () {

    Route::get('/', Index::class)->name('index');
    Route::get('/create', \App\Livewire\Rab\Create::class)->name('create');

    Route::get('/json', function () {
        $data = Rab::with(['sudin', 'district', 'subdistrict', 'user'])->get()->map(fn($r) => [
            'nomor' => $r->nomor,
            'name' => $r->name,
            'sudin' => $r->sudin?->name ?? '-',
            'district' => $r->district?->name ?? '-',
            'user' => $r->user?->name ?? '-',
            'tanggal_mulai' => $r->tanggal_mulai?->format('d/m/Y') ?? '-',
            'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $r->status_text .
                '</span>',
            'action' => '<a href="' . route('rab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{rab}/json', function (Rab $rab) {
        $data = $rab->items()->with('item')->get()->map(fn($r) => [
            'item' => $r->item?->spec ?? '-',
            'code' => $r->item?->code ?? '-',
            'qty' => (int) $r->qty,
            'action' => '<a href="' . route('rab.show', $rab->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/{rab}', Show::class)->name('show');

});