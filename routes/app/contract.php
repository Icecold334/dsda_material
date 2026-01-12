<?php

use App\Models\Contract;
use App\Livewire\Contract\Show;
use App\Livewire\Contract\Index;
use App\Livewire\Contract\Create;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::prefix('contract')->name('contract.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Contract::all()->map(fn($c) => [
            'nomor' => $c->nomor,
            'status' => '<span class="bg-' . $c->status_color . '-600 text-' . $c->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $c->status_text .
                '</span>',
            'action' => '<a href="' . route('contract.show', $c->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{contract}/json', function (Contract $contract) {
        $data = $contract->items->map(fn($c) => [
            'code' => $c->item->code,
            'item' => $c->item->category->name . ' | ' . $c->item->spec,
            'qty' => (int) $c->qty . ' ' . $c->item->category->unit->name,
            'action' => '<a href="' . route('contract.show', $contract->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');

    Route::get('/api/emonev', function (Request $request) {

        $nomorContract = trim(request('nomor_kontrak'));
        $tahun = trim(request('tahun'));

        $contractExist = Contract::where('nomor', $nomorContract)->get()->count();

        if ($contractExist) {
            return response()->json(['status' => 'error', 'data' => 'Kontrak sudah terdaftar!']);
        }


        // validasi
        if (!$nomorContract || !$tahun) {
            return back()->with('error', 'Nomor contract dan tahun wajib diisi');
        }

        // hit API
        $response = Http::timeout(180)
            ->withBasicAuth(
                'inventa',
                config('app.api_emonev_key')
            )
            ->get(rtrim(config('app.api_emonev'), '/') . '/' . $tahun);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data contract dari API');
        }
        $data = $response->json('data');

        $contract = collect($data)->first(
            fn($item) =>
            isset($item['no_spk']) &&
            strcasecmp(trim($item['no_spk']), $nomorContract) === 0
        );

        if (!$contract) {
            return back()->with('error', 'Nomor contract tidak ditemukan');
        }
        return response()->json(['status' => 'success', 'data' => $contract]);
    })->name('emonev');
    Route::get('/create', Create::class)->name('create');
    Route::get('/{contract}', Show::class)->name('show');


});
