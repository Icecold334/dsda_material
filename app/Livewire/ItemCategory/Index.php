<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Kategori Barang')]

    public $editItemCategoryId = null;

    #[On('item-category-created')]
    #[On('item-category-updated')]
    #[On('deleteItemCategory')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editItemCategory($itemCategoryId)
    {
        $this->editItemCategoryId = $itemCategoryId;
        $this->dispatch('open-modal', 'edit-item-category-' . $itemCategoryId);
    }

    #[On('deleteItemCategory')]
    public function deleteItemCategory($itemCategoryId)
    {
        $itemCategory = ItemCategory::find($itemCategoryId);
        if ($itemCategory) {
            $itemCategory->delete();
            $this->dispatch('refresh-grid');
            $this->dispatch('item-category-deleted');
        }
    }

    public function render()
    {
        return view('livewire.item-category.index', [
            'itemCategories' => ItemCategory::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Kategori Barang',
        ];
    }
}
