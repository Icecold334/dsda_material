<?php

namespace App\Livewire\Contract;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Http;

class Create extends Component
{
    #[Title("Tambah Kontrak")]
    public $build = true, $listCount = 0;
    public $nomorContract, $contractYear, $apiExist;

    // #[On("cari-contract")]
    // public function cariContract()
    // {
    //     $nomorContract = trim($this->nomorContract);
    //     $contractYear = trim($this->contractYear);
    //     return $this->dispatch(
    //         'confirm',
    //         title: 'Yakin ingin menghapus?',
    //         text: 'Data yang dihapus tidak bisa dikembalikan',
    //         callbackEvent: 'confirm-result',
    //         callbackData: ['memek' => 'jancok'],
    //     );
    //     if (!$contractYear || !$nomorContract) {
    //         $this->dispatch(
    //             'alert',
    //             type: 'error',
    //             title: 'Lengkapi Data!',
    //             // text: 'Nomor contract dan tahun wajib diisi.',
    //         );

    //         return;
    //     }


    //     $response = Http::timeout(180)
    //         ->withBasicAuth('inventa', 'aF7xPq92LmZTkw38RbCn0vMUyJDg1shKXtbEWuAQ5oYclVGriHzSmNd6jeLfOBT3')
    //         ->get(rtrim(config('app.api_emonev'), '/') . '/' . $contractYear);



    //     if (!$response->successful()) {
    //         return session()->flash('error', 'Gagal mengambil data contract dari API');
    //     }

    //     $data = $response->json()['data'];
    //     /**
    //      * Cocokkan nomor contract dari batch JSON
    //      * asumsi struktur data berupa array contract
    //      */
    //     $contract = collect($data)->first(function ($item) use ($nomorContract) {
    //         return isset($item['no_spk'])
    //             && strcasecmp(trim($item['no_spk']), $nomorContract) === 0;
    //     });

    //     if (!$contract) {
    //         return session()->flash('error', 'Nomor contract tidak ditemukan');
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
        if ($this->build) {
            $this->nomorContract = '20397/PN01.05';
            $this->contractYear = '2025';
            $this->apiExist = false;
        } else {
            $this->dispatch('open-modal', 'input-contract-number');
        }
    }

    #[On("confirmContract")]
    public function confirmResult($data)
    {
        $this->dispatch('endLoading');
        $this->dispatch('open-modal', 'confirm-contract');
    }

    #[On("proceedCreateContract")]
    public function proceedCreateContract($data)
    {
        $this->nomorContract = $data['no_spk'];
        $this->contractYear = $data['tahun_anggaran'];
        $this->apiExist = true;
        $this->dispatch('proceedCreateContractAgain', data: [
            'dataContract' => $data,
            'no_spk' => $data['no_spk'],
            'tahun_anggaran' => $data['tahun_anggaran'],
        ]);
    }

    #[On("listCountUpdated")]
    public function updateListCount($count)
    {
        $this->listCount = $count;
    }

    public function render()
    {

        return view('livewire.contract.create');
    }
}
