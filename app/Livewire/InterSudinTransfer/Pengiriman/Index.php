<?php

namespace App\Livewire\InterSudinTransfer\Pengiriman;

use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('Daftar Transfer Pengiriman')]

    public function render()
    {
        return view('livewire.inter-sudin-transfer.pengiriman.index');
    }
}
