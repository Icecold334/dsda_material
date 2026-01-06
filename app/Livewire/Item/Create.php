<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $sudin_id = '';
    public $item_category_id = '';
    public $spec = '';
    public $unit = '';
    public $active = true;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'item_category_id' => 'nullable|exists:item_categories,id',
            'spec' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'active' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        Item::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'item_category_id' => $this->item_category_id ?: null,
            'spec' => $this->spec,
            'unit' => $this->unit,
            'active' => $this->active,
        ]);

        session()->flash('message', 'Barang berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-item');
        $this->dispatch('item-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.item.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'categories' => ItemCategory::orderBy('name')->get(),
        ]);
    }
}
