<?php

namespace App\Livewire\Kontrak;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Http;

class Create extends Component
{
    #[Title("Tambah Kontrak")]
    public $nomor_kontrak;

    #[On("cari-kontrak")]
    public function cariKontrak()
    {

        $nomor_kontrak = $this->nomor_kontrak;

        $response = Http::withHeaders([
            'X-API-KEY' => config('app.api_emonev_key'),
        ])->get(
                config('app.api_emonev'),
                // [
                //     'nomor_kontrak' => $nomor_kontrak,
                // ]
            );

        return dd($response->json());
    }
    public function render()
    {
        $this->dispatch('open-modal', 'input-nomor-kontrak');
        return view('livewire.kontrak.create');
    }
}
