<?php

namespace App\Livewire\ItemCategory;

use App\Models\ItemCategory;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

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

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'item_unit_id' => $this->item_unit_id,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama barang wajib diisi',
                'name.string' => 'Nama barang harus berupa teks',
                'name.max' => 'Nama barang maksimal 255 karakter',
                'item_unit_id.exists' => 'Satuan yang dipilih tidak valid',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-item-category')]
    public function confirmSave()
    {
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
