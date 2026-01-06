<?php

namespace App\Livewire\Item;

use Livewire\Component;
use App\Models\Item;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Barang')]

    public Item $item;

    public function render()
    {
        return view('livewire.item.show');
    }
}
