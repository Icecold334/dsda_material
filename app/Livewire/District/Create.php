<?php

namespace App\Livewire\District;

use App\Models\Division;
use App\Models\Sudin;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

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

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'sudin_id' => $this->sudin_id,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama kecamatan wajib diisi',
                'name.string' => 'Nama kecamatan harus berupa teks',
                'name.max' => 'Nama kecamatan maksimal 255 karakter',
                'sudin_id.required' => 'Sudin wajib dipilih',
                'sudin_id.exists' => 'Sudin yang dipilih tidak valid',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-district')]
    public function confirmSave()
    {
        Division::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'type' => 'district',  // kecamatan
        ]);

        $this->dispatch('close-modal', 'create-district');
        $this->dispatch('success-created', message: 'Kecamatan berhasil ditambahkan');
        $this->dispatch('district-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.district.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
