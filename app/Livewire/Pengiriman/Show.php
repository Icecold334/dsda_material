<?php

namespace App\Livewire\Pengiriman;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Pengiriman')]
    public Delivery $pengiriman;
    public function render()
    {
        return view('livewire.pengiriman.show');
    }
}
