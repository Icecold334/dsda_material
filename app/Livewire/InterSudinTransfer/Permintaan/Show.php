<?php

namespace App\Livewire\InterSudinTransfer\Permintaan;

use App\Models\InterSudinTransfer;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Transfer Permintaan')]

    public InterSudinTransfer $transfer;

    public function render()
    {
        return view('livewire.inter-sudin-transfer.permintaan.show');
    }
}
