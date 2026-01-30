<?php

namespace App\Livewire\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Barang')]
    public ItemCategory $itemCategory;
    public $editItemId = null;

    public $data = [];

    #[On('item-created')]
    #[On('item-updated')]
    #[On('deleteItem')]

    public function mount()
    {
        $this->data = [
            ["name" => "Spesifikasi", "id" => "name", "width" => "40%"],
            ["name" => "Sudin", "id" => "sudin", "width" => "30%"],
            ["name" => "Status", "id" => "status", "width" => "15%"],
            ["name" => "", "id" => "action", "width" => "15%", "sortable" => false]
        ];
    }

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
