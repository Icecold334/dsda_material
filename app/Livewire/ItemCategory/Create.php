<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $item_unit_id = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'item_unit_id' => 'nullable|exists:item_units,id',
        ];
    }

    public function save()
    {
        $this->validate();

        ItemCategory::create([
            'name' => $this->name,
            'item_unit_id' => $this->item_unit_id ?: null,
        ]);

        $this->dispatch('close-modal', 'create-item-category');
        $this->dispatch('success-created', message: 'Kategori barang berhasil ditambahkan');
        $this->dispatch('item-category-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.item-category.create', [
            'units' => \App\Models\ItemUnit::orderBy('name')->get(),
        ]);
    }
}
