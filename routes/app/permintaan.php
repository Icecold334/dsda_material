<?php

use App\Models\RequestModel;
use Illuminate\Support\Facades\Route;
use App\Livewire\Permintaan\Rab\Show as ShowRab;
use App\Livewire\Permintaan\Rab\Index as IndexRab;
use App\Livewire\Permintaan\Rab\Create as CreateRab;
use App\Livewire\Permintaan\NonRab\Show as ShowNonRab;
use App\Livewire\Permintaan\NonRab\Index as IndexNonRab;
use App\Livewire\Permintaan\NonRab\Create as CreateNonRab;


Route::prefix('permintaan')->name('permintaan.')->group(function () {

    Route::prefix('rab')->name('rab.')->group(function () {

        Route::get('/', IndexRab::class)->name('index');
        Route::get('/create', CreateRab::class)->name('create');

        Route::get('/json', function () {
            $data = RequestModel::whereNotNull('rab_id')->get()->map(fn($r) => [
                'nomor' => $r->nomor,
                'user' => $r->user->name,
                'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                    . $r->status_text .
                    '</span>',
                'action' => '<a href="' . route('permintaan.rab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');

        Route::get('/{permintaan}/json', function (RequestModel $permintaan) {
            $data = $permintaan->items->map(fn($r) => [
                'kode' => $r->item->code,
                'item' => $r->item->name,
                'qty' => (int) $r->qty_approved . ' ' . $r->item->unit,
                'action' => '<a href="' . route('rab.show', $permintaan->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('show.json');

        Route::get('/{permintaan}', ShowRab::class)->name('show');
    });
    Route::prefix('non-rab')->name('nonRab.')->group(function () {
        Route::get('/', IndexNonRab::class)->name('index');
        Route::get('/create', CreateNonRab::class)->name('create');
        Route::get('/json', function () {
            $data = RequestModel::whereNull('rab_id')->get()->map(fn($r) => [
                'nomor' => $r->nomor,
                'user' => $r->user->name,
                'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                    . $r->status_text .
                    '</span>',
                'action' => '<a href="' . route('permintaan.nonRab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');
        Route::get('/{permintaan}', ShowNonRab::class)->name('show');
    });


});