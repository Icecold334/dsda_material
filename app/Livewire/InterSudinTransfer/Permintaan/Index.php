<?php

namespace App\Livewire\InterSudinTransfer\Permintaan;

use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('Daftar Transfer Permintaan')]

    public function render()
    {
        return view('livewire.inter-sudin-transfer.permintaan.index');
    }
}
