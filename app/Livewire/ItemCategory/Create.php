<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use App\Models\Sudin;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $sudin_id = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
        ];
    }

    public function save()
    {
        $this->validate();

        ItemCategory::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
        ]);

        session()->flash('message', 'Kategori barang berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-item-category');
        $this->dispatch('item-category-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.item-category.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
