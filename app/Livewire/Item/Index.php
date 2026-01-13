<?php

namespace App\Livewire\Item;

use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Spesifikasi')]

    public $editItemId = null;

    #[On('item-created')]
    #[On('item-updated')]
    #[On('deleteItem')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editItem($itemId)
    {
        $this->editItemId = $itemId;
        $this->dispatch('open-modal', 'edit-item-' . $itemId);
    }

    #[On('deleteItem')]
    public function deleteItem($itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            $item->delete();
            $this->dispatch('success-deleted', message: 'Barang berhasil dihapus');
            $this->dispatch('item-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.item.index', [
            'items' => Item::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Barang',
        ];
    }
}
