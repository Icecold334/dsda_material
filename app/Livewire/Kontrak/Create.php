<?php

namespace App\Livewire\Kontrak;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Http;

class Create extends Component
{
    #[Title("Tambah Kontrak")]
    public $nomorKontrak, $tahunKontrak, $apiExist;

    // #[On("cari-kontrak")]
    // public function cariKontrak()
    // {
    //     $nomorKontrak = trim($this->nomorKontrak);
    //     $tahunKontrak = trim($this->tahunKontrak);
    //     return $this->dispatch(
    //         'confirm',
    //         title: 'Yakin ingin menghapus?',
    //         text: 'Data yang dihapus tidak bisa dikembalikan',
    //         callbackEvent: 'confirm-result',
    //         callbackData: ['memek' => 'jancok'],
    //     );
    //     if (!$tahunKontrak || !$nomorKontrak) {
    //         $this->dispatch(
    //             'alert',
    //             type: 'error',
    //             title: 'Lengkapi Data!',
    //             // text: 'Nomor kontrak dan tahun wajib diisi.',
    //         );

    //         return;
    //     }


    //     $response = Http::timeout(180)
    //         ->withBasicAuth('inventa', 'aF7xPq92LmZTkw38RbCn0vMUyJDg1shKXtbEWuAQ5oYclVGriHzSmNd6jeLfOBT3')
    //         ->get(rtrim(config('app.api_emonev'), '/') . '/' . $tahunKontrak);



    //     if (!$response->successful()) {
    //         return session()->flash('error', 'Gagal mengambil data kontrak dari API');
    //     }

    //     $data = $response->json()['data'];
    //     /**
    //      * Cocokkan nomor kontrak dari batch JSON
    //      * asumsi struktur data berupa array kontrak
    //      */
    //     $kontrak = collect($data)->first(function ($item) use ($nomorKontrak) {
    //         return isset($item['no_spk'])
    //             && strcasecmp(trim($item['no_spk']), $nomorKontrak) === 0;
    //     });

    //     if (!$kontrak) {
    //         return session()->flash('error', 'Nomor kontrak tidak ditemukan');
    //     }

    //     // kalau ketemu
    //     return $this->dispatch(
    //         'alert',
    //         mode: 'confirm',
    //         type: 'warning',
    //         title: 'Yakin ingin menghapus?',
    //         text: 'Data yang dihapus tidak dapat dikembalikan!',
    //         confirmButtonText: 'Ya, hapus!',
    //         cancelButtonText: 'Batal',
    //         confirmEvent: 'delete-driver',
    //     );

    // }
    public function mount()
    {

        $this->dispatch('open-modal', 'input-nomor-kontrak');
    }

    #[On("confirmKontrak")]
    public function confirmResult($data)
    {
        $this->dispatch('endLoading');
        $this->dispatch('open-modal', 'confirm-kontrak');
    }

    #[On("proceedCreateKontrak")]
    public function proceedCreateKontrak($data)
    {
        $this->nomorKontrak = $data['no_spk'];
        $this->tahunKontrak = $data['tahun_anggaran'];
        $this->apiExist = true;
    }


    public function render()
    {

        return view('livewire.kontrak.create');
    }
}
