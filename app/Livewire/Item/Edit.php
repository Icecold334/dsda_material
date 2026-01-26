<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Sudin;
use App\Models\ItemCategory;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class Edit extends Component
{
    public Item $item;
    public $spec = '';
    public $sudin_id = '';
    public $item_category_id = '';
    public $active = true;

    public function mount()
    {
        $this->spec = $this->item->spec;
        $this->sudin_id = $this->item->sudin_id;
        $this->item_category_id = $this->item->item_category_id;
        $this->active = $this->item->active;
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

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'spec' => $this->spec,
                'sudin_id' => $this->sudin_id,
                'item_category_id' => $this->item_category_id,
                'active' => $this->active,
            ],
            $this->rules(),
            [
                'spec.required' => 'Spesifikasi barang wajib diisi',
                'spec.string' => 'Spesifikasi barang harus berupa teks',
                'spec.max' => 'Spesifikasi barang maksimal 255 karakter',
                'sudin_id.required' => 'Sudin wajib dipilih',
                'sudin_id.exists' => 'Sudin yang dipilih tidak valid',
                'item_category_id.exists' => 'Kategori barang yang dipilih tidak valid',
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

    #[On('confirm-update-item')]
    public function confirmUpdate()
    {
        $this->item->update([
            'spec' => $this->spec,
            'sudin_id' => $this->sudin_id,
            'item_category_id' => $this->item_category_id ?: null,
            'active' => $this->active,
        ]);

        $this->dispatch('close-modal', 'edit-item-' . $this->item->id);
        $this->dispatch('success-updated', message: 'Barang berhasil diperbarui');
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
