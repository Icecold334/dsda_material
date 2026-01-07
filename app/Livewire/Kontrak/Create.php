<?php

namespace App\Livewire\Kontrak;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Http;

class Create extends Component
{
    #[Title("Tambah Kontrak")]
    public $nomorKontrak, $tahunKontrak;

    #[On("cari-kontrak")]
    public function cariKontrak()
    {
        $nomorKontrak = trim($this->nomorKontrak);
        $tahunKontrak = trim($this->tahunKontrak);

        // validasi sederhana
        if (!$tahunKontrak || !$nomorKontrak) {
            return $this->dispatch('sudin-created');
            ;
        }

        // hit API: /api/kontrak/{tahun}
        $response = Http::withHeaders([
            'X-API-KEY' => config('app.api_emonev_key'),
        ])->get(
                rtrim(config('app.api_emonev'), '/') . '/' . $tahunKontrak
            );

        if (!$response->successful()) {
            return session()->flash('error', 'Gagal mengambil data kontrak dari API');
        }

        $data = $response->json();

        /**
         * Cocokkan nomor kontrak dari batch JSON
         * asumsi struktur data berupa array kontrak
         */
        $kontrak = collect($data)->first(function ($item) use ($nomorKontrak) {
            return isset($item['nomor_kontrak'])
                && strcasecmp(trim($item['nomor_kontrak']), $nomorKontrak) === 0;
        });

        if (!$kontrak) {
            return session()->flash('error', 'Nomor kontrak tidak ditemukan');
        }

        // kalau ketemu
        dd($kontrak);
    }
    public function render()
    {
        $this->dispatch('open-modal', 'input-nomor-kontrak');
        return view('livewire.kontrak.create');
    }
}
