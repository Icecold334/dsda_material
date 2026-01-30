<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Component;
use App\Models\RequestModel;
use Livewire\Attributes\Title;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    public $data = [];

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
            'status' => $this->permintaan->status,
            'status_text' => $this->permintaan->status_text,
            'status_color' => $this->permintaan->status_color,
            'pemohon' => $this->permintaan->user?->name,
            'item_type' => $this->permintaan->itemType?->name,
            'rab_nomor' => $this->permintaan->rab?->nomor,
            'rab_tahun' => $this->permintaan->rab?->tahun,
            'rab_id' => $this->permintaan->rab_id,
            'rab' => $this->permintaan->rab,
        ]);

        $this->data = [
            ["name" => "No", "id" => "no", "width" => "8%"],
            ["name" => "Kode Barang", "id" => "kode", "width" => "12%"],
            ["name" => "Barang", "id" => "barang", "width" => "15%"],
            ["name" => "Spesifikasi", "id" => "spec"],
            ["name" => "Jumlah Diminta", "id" => "qty_request", "width" => "15%"],
            ["name" => "Jumlah Disetujui", "id" => "qty_approved", "width" => "15%"]
        ];
    }

    public function render()
    {
        return view('livewire.permintaan.rab.show');
    }
}
