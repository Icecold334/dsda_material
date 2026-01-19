<?php

namespace App\Livewire\ItemType;

use App\Models\ItemType;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public ItemType $itemType;
    public $name = '';
    public $active = true;

    public function mount()
    {
        $this->name = $this->itemType->name;
        $this->active = (bool) $this->itemType->active;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'active' => 'boolean',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->itemType->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'active' => $this->active,
        ]);

        $this->dispatch('close-modal', 'edit-item-type-' . $this->itemType->id);
        $this->dispatch('success-updated', message: 'Tipe Barang berhasil diperbarui');
        $this->dispatch('item-type-updated');
    }

    public function render()
    {
        return view('livewire.item-type.edit');
    }
}
