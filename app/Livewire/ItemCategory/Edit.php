<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use Livewire\Component;

class Edit extends Component
{
    public ItemCategory $itemCategory;
    public $name = '';
    public $item_unit_id = '';

    public function mount()
    {
        $this->name = $this->itemCategory->name;
        $this->item_unit_id = $this->itemCategory->item_unit_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'item_unit_id' => 'nullable|exists:item_units,id',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->itemCategory->update([
            'name' => $this->name,
            'item_unit_id' => $this->item_unit_id ?: null,
        ]);

        $this->dispatch('close-modal', 'edit-item-category-' . $this->itemCategory->id);
        $this->dispatch('success-updated', message: 'Kategori barang berhasil diperbarui');
        $this->dispatch('item-category-updated');
    }

    public function render()
    {
        return view('livewire.item-category.edit', [
            'units' => \App\Models\ItemUnit::orderBy('name')->get(),
        ]);
    }
}
