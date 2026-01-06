<?php

namespace App\Livewire\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Kategori Barang')]

    public ItemCategory $itemCategory;
    public $editItemId = null;

    #[On('item-created')]
    #[On('item-updated')]
    #[On('deleteItem')]
    public function refreshItems()
    {
        $this->dispatch('refresh-grid');
    }

    #[On('deleteItem')]
    public function deleteItem($itemId)
    {
        $item = Item::find($itemId);
        if ($item && $item->item_category_id === $this->itemCategory->id) {
            $item->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('item-deleted');
        }
    }

    public function render()
    {
        return view('livewire.item-category.show', [
            'items' => $this->itemCategory->items,
        ]);
    }
}
