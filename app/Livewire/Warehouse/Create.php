<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Models\Sudin;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class Create extends Component
{
    public $name = '';
    public $sudin_id = '';
    public $location = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'sudin_id' => $this->sudin_id,
                'location' => $this->location,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama gudang wajib diisi',
                'name.string' => 'Nama gudang harus berupa teks',
                'name.max' => 'Nama gudang maksimal 255 karakter',
                'sudin_id.required' => 'Sudin wajib dipilih',
                'sudin_id.exists' => 'Sudin yang dipilih tidak valid',
                'location.string' => 'Lokasi harus berupa teks',
                'location.max' => 'Lokasi maksimal 255 karakter',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-warehouse')]
    public function confirmSave()
    {
        Warehouse::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'location' => $this->location,
        ]);

        $this->dispatch('close-modal', 'create-warehouse');
        $this->dispatch('success-created', message: 'Gudang berhasil ditambahkan');
        $this->dispatch('warehouse-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.warehouse.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
