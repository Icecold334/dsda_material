<?php

namespace App\Livewire\ItemType;

use App\Models\ItemType;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Tipe Barang')]

    public $editItemTypeId = null;

    #[On('item-type-created')]
    #[On('item-type-updated')]
    #[On('deleteItemType')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editItemType($itemTypeId)
    {
        $this->editItemTypeId = $itemTypeId;
        $this->dispatch('open-modal', 'edit-item-type-' . $itemTypeId);
    }

    #[On('deleteItemType')]
    public function deleteItemType($itemTypeId)
    {
        $itemType = ItemType::find($itemTypeId);
        if ($itemType) {
            $itemType->delete();
            $this->dispatch('success-deleted', message: 'Tipe Barang berhasil dihapus');
            $this->dispatch('item-type-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.item-type.index', [
            'itemTypes' => ItemType::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Tipe Barang',
        ];
    }
}
