<?php

namespace App\Livewire\ItemType;

use App\Models\ItemType;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

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

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'active' => $this->active,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama tipe barang wajib diisi',
                'name.string' => 'Nama tipe barang harus berupa teks',
                'name.max' => 'Nama tipe barang maksimal 255 karakter',
                'active.boolean' => 'Status aktif harus berupa boolean',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-item-type')]
    public function confirmSave()
    {
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
