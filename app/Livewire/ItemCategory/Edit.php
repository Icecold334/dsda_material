<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public ItemCategory $itemCategory;
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->itemCategory->name;
        $this->sudin_id = $this->itemCategory->sudin_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
        ];
    }

    #[\Livewire\Attributes\On('confirmUpdateItemCategory')]
    public function update($itemCategoryId = null)
    {
        $this->validate();

        $this->itemCategory->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
        ]);

        $this->dispatch('close-modal', 'edit-item-category-' . $this->itemCategory->id);
        $this->dispatch('item-category-updated');
    }

    public function render()
    {
        return view('livewire.item-category.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
