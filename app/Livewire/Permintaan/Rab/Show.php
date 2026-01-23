<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Component;
use App\Models\RequestModel;
use Livewire\Attributes\Title;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    public function mount()
    {
        // Dispatch event untuk set data permintaan ke modal
        $this->dispatch('setRequestData', [
            'nomor' => $this->permintaan->nomor,
            'name' => $this->permintaan->name,
            'sudin_id' => $this->permintaan->sudin_id,
            'warehouse_id' => $this->permintaan->warehouse_id,
            'district_id' => $this->permintaan->district_id,
            'subdistrict_id' => $this->permintaan->subdistrict_id,
            'tanggal_permintaan' => $this->permintaan->tanggal_permintaan?->format('Y-m-d'),
            'address' => $this->permintaan->address,
            'panjang' => $this->permintaan->panjang,
            'lebar' => $this->permintaan->lebar,
            'tinggi' => $this->permintaan->tinggi,
            'notes' => $this->permintaan->notes,
            'rab' => $this->permintaan->rab,
        ]);
    }

    public function render()
    {
        return view('livewire.permintaan.rab.show');
    }
}
