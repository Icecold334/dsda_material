<?php

namespace App\Livewire\InterSudinTransfer\Pengiriman;

use App\Models\InterSudinTransfer;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Transfer Pengiriman')]

    public InterSudinTransfer $transfer;

    public function render()
    {
        return view('livewire.inter-sudin-transfer.pengiriman.show');
    }
}
