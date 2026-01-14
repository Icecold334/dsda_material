<?php

use App\Models\InterSudinTransfer;
use Illuminate\Support\Facades\Route;
use App\Livewire\InterSudinTransfer\Permintaan\Show as ShowPermintaan;
use App\Livewire\InterSudinTransfer\Permintaan\Index as IndexPermintaan;
use App\Livewire\InterSudinTransfer\Permintaan\Create as CreatePermintaan;
use App\Livewire\InterSudinTransfer\Pengiriman\Show as ShowPengiriman;
use App\Livewire\InterSudinTransfer\Pengiriman\Index as IndexPengiriman;

Route::prefix('transfer')->name('transfer.')->group(function () {

    // Permintaan - Transfer yang kita buat (meminta ke sudin lain)
    Route::prefix('permintaan')->name('permintaan.')->group(function () {

        Route::get('/', IndexPermintaan::class)->name('index');
        Route::get('/create', CreatePermintaan::class)->name('create');

        Route::get('/json', function () {
            $data = InterSudinTransfer::with(['sudinPengirim', 'sudinPenerima', 'user'])
                ->latest()
                ->get()
                ->map(fn($t) => [
                    'tanggal' => $t->tanggal_transfer?->format('d/m/Y') ?? '-',
                    'sudin_pengirim' => $t->sudinPengirim?->name ?? '-',
                    'sudin_penerima' => $t->sudinPenerima?->name ?? '-',
                    'user' => $t->user->name,
                    'status' => '<span class="bg-' . $t->status_color . '-600 text-' . $t->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                        . $t->status_text .
                        '</span>',
                    'action' => '<a href="' . route('transfer.permintaan.show', $t->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');

        Route::get('/{transfer}/json', function (InterSudinTransfer $transfer) {
            $data = $transfer->load(['items.item.category.unit'])->items->map(function ($item, $index) {
                return [
                    'no' => $index + 1,
                    'kode' => $item->item->code ?? '-',
                    'barang' => $item->item->category->name ?? '-',
                    'spec' => $item->item->spec ?? '-',
                    'qty' => number_format($item->qty, 2) . ' ' . ($item->item->category->unit->name ?? ''),
                    'notes' => $item->notes ?? '-',
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('show.json');

        Route::get('/{transfer}', ShowPermintaan::class)->name('show');
    });

    // Pengiriman - Transfer yang diminta ke kita (dari sudin lain)
    Route::prefix('pengiriman')->name('pengiriman.')->group(function () {

        Route::get('/', IndexPengiriman::class)->name('index');

        Route::get('/json', function () {
            $data = InterSudinTransfer::with(['sudinPengirim', 'sudinPenerima', 'user'])
                ->latest()
                ->get()
                ->map(fn($t) => [
                    'tanggal' => $t->tanggal_transfer?->format('d/m/Y') ?? '-',
                    'sudin_pengirim' => $t->sudinPengirim?->name ?? '-',
                    'sudin_penerima' => $t->sudinPenerima?->name ?? '-',
                    'user' => $t->user->name,
                    'status' => '<span class="bg-' . $t->status_color . '-600 text-' . $t->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                        . $t->status_text .
                        '</span>',
                    'action' => '<a href="' . route('transfer.pengiriman.show', $t->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');

        Route::get('/{transfer}/json', function (InterSudinTransfer $transfer) {
            $data = $transfer->load(['items.item.category.unit'])->items->map(function ($item, $index) {
                return [
                    'no' => $index + 1,
                    'kode' => $item->item->code ?? '-',
                    'barang' => $item->item->category->name ?? '-',
                    'spec' => $item->item->spec ?? '-',
                    'qty' => number_format($item->qty, 2) . ' ' . ($item->item->category->unit->name ?? ''),
                    'notes' => $item->notes ?? '-',
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('show.json');

        Route::get('/{transfer}', ShowPengiriman::class)->name('show');
    });
});
