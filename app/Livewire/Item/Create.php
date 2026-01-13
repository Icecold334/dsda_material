<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;
use Livewire\Component;

class Create extends Component
{
    public ?ItemCategory $itemCategory = null;
    public $spec = '';
    public $sudin_id = '';
    public $item_category_id = '';
    public $active = true;

    public function mount()
    {
        if ($this->itemCategory) {
            $this->item_category_id = $this->itemCategory->id;
            $this->sudin_id = $this->itemCategory->sudin_id;
        }
    }

    public function rules()
    {
        return [
            'spec' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'item_category_id' => 'nullable|exists:item_categories,id',
            'active' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        Item::create([
            'spec' => $this->spec,
            'sudin_id' => $this->sudin_id,
            'item_category_id' => $this->item_category_id ?: null,
            'active' => $this->active,
        ]);

        $modalName = $this->itemCategory ? 'create-item-' . $this->itemCategory->id : 'create-item';
        $this->dispatch('close-modal', $modalName);
        $this->dispatch('success-created', message: 'Barang berhasil ditambahkan');
        $this->dispatch('item-created');

        $this->reset(['spec', 'active']);
        if (!$this->itemCategory) {
            $this->reset(['sudin_id', 'item_category_id']);
        }
    }

    public function render()
    {
        return view('livewire.item.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'categories' => ItemCategory::orderBy('name')->get(),
        ]);
    }
}
