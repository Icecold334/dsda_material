<?php

namespace App\Livewire\Stock;

use App\Models\Warehouse;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Stok Gudang')]

    public function render()
    {
        return view('livewire.stock.index');
    }
}
