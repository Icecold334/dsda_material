<?php

namespace App\Livewire\ItemType;

use App\Models\ItemType;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

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

        $this->dispatch('validation-passed-update');
        return;
    }

    #[On('confirm-update-item-type')]
    public function confirmUpdate()
    {
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
