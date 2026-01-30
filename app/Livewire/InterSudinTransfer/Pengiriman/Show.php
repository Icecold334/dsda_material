<?php

namespace App\Livewire\InterSudinTransfer\Pengiriman;

use App\Models\InterSudinTransfer;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Transfer Pengiriman')]

    public InterSudinTransfer $transfer;

    public $data = [];

    public function mount()
    {
        // Dispatch transfer data to modal
        $this->dispatch('setTransferData', [
            'id' => $this->transfer->id,
            'sudin_pengirim_id' => $this->transfer->sudin_pengirim_id,
            'sudin_penerima_id' => $this->transfer->sudin_penerima_id,
            'tanggal_transfer' => $this->transfer->tanggal_transfer?->format('Y-m-d'),
            'notes' => $this->transfer->notes,
            'status' => $this->transfer->status,
            'status_text' => $this->transfer->status_text,
            'status_color' => $this->transfer->status_color,
            'pembuat' => $this->transfer->user?->name,
        ]);

        $this->data = [
            ["name" => "No", "id" => "no", "width" => "8%"],
            ["name" => "Kode Barang", "id" => "kode", "width" => "12%"],
            ["name" => "Barang", "id" => "barang", "width" => "20%"],
            ["name" => "Spesifikasi", "id" => "spec"],
            ["name" => "Jumlah", "id" => "qty", "width" => "15%"],
            ["name" => "Catatan", "id" => "notes", "width" => "15%"],
        ];
    }

    public function render()
    {
        return view('livewire.inter-sudin-transfer.pengiriman.show');
    }
}
