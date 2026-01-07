<?php

use App\Models\Contract;
use App\Livewire\Kontrak\Show;
use App\Livewire\Kontrak\Index;
use App\Livewire\Kontrak\Create;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::prefix('kontrak')->name('kontrak.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Contract::all()->map(fn($c) => [
            'nomor' => $c->nomor,
            'status' => '<span class="bg-' . $c->status_color . '-600 text-' . $c->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $c->status_text .
                '</span>',
            'action' => '<a href="' . route('kontrak.show', $c->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{contract}/json', function (Contract $contract) {
        $data = $contract->items->map(fn($c) => [
            'item' => $c->item->name,
            'qty' => (int) $c->qty . ' ' . $c->item->unit,
            'action' => '<a href="' . route('kontrak.show', $contract->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/api/emonev', function (Request $request) {

        $nomorKontrak = trim(request('nomor_kontrak'));
        $tahun = trim(request('tahun'));


        // validasi
        if (!$nomorKontrak || !$tahun) {
            return back()->with('error', 'Nomor kontrak dan tahun wajib diisi');
        }

        // hit API
        $response = Http::timeout(180)
            ->withBasicAuth(
                'inventa',
                config('app.api_emonev_key')
            )
            ->get(rtrim(config('app.api_emonev'), '/') . '/' . $tahun);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data kontrak dari API');
        }
        $data = $response->json('data');

        $kontrak = collect($data)->first(
            fn($item) =>
            isset($item['no_spk']) &&
            strcasecmp(trim($item['no_spk']), $nomorKontrak) === 0
        );

        if (!$kontrak) {
            return back()->with('error', 'Nomor kontrak tidak ditemukan');
        }
        return response()->json(['status' => 'success', 'data' => $kontrak]);
    })->name('emonev');
    Route::get('/create', Create::class)->name('create');
    Route::get('/{contract}', Show::class)->name('show');


});
