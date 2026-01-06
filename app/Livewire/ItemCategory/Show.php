<?php

namespace App\Livewire\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Kategori Barang')]

    public ItemCategory $itemCategory;

    public function render()
    {
        return view('livewire.item-category.show');
    }
}
