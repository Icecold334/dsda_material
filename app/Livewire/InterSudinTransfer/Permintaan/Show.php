<?php

namespace App\Livewire\InterSudinTransfer\Permintaan;

use App\Models\InterSudinTransfer;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Transfer Permintaan')]

    public InterSudinTransfer $transfer;

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
    }

    public function render()
    {
        return view('livewire.inter-sudin-transfer.permintaan.show');
    }
}
