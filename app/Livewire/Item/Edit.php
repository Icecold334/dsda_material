<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;
use Livewire\Component;

class Edit extends Component
{
    public Item $item;
    public $name = '';
    public $sudin_id = '';
    public $item_category_id = '';
    public $spec = '';
    public $unit = '';
    public $active = true;

    public function mount()
    {
        $this->name = $this->item->name;
        $this->sudin_id = $this->item->sudin_id;
        $this->item_category_id = $this->item->item_category_id;
        $this->spec = $this->item->spec;
        $this->unit = $this->item->unit;
        $this->active = $this->item->active;
    }

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

    #[\Livewire\Attributes\On('confirmUpdateItem')]
    public function update($itemId = null)
    {
        $this->validate();

        $this->item->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'item_category_id' => $this->item_category_id ?: null,
            'spec' => $this->spec,
            'unit' => $this->unit,
            'active' => $this->active,
        ]);

        $this->dispatch('close-modal', 'edit-item-' . $this->item->id);
        $this->dispatch('item-updated');
    }

    public function render()
    {
        return view('livewire.item.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
            'categories' => ItemCategory::orderBy('name')->get(),
        ]);
    }
}
