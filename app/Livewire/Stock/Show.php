<?php

namespace App\Livewire\Stock;

use App\Models\Warehouse;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Stok Gudang')]

    public Warehouse $warehouse;

    public function render()
    {
        $this->warehouse->load('sudin');

        return view('livewire.stock.show');
    }
}
