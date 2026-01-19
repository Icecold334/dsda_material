<?php

namespace App\Livewire\ItemType;

use App\Models\ItemType;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $active = true;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'active' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        ItemType::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'active' => $this->active,
        ]);

        $this->dispatch('close-modal', 'create-item-type');
        $this->dispatch('success-created', message: 'Tipe Barang berhasil ditambahkan');
        $this->dispatch('item-type-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.item-type.create');
    }
}
