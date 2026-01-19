<?php

namespace App\Livewire\ItemType;

use Livewire\Component;
use App\Models\ItemType;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Tipe Barang')]

    public ItemType $itemType;

    public function render()
    {
        return view('livewire.item-type.show');
    }
}
