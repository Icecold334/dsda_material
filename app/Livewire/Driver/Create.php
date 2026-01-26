<?php

namespace App\Livewire\Driver;

use App\Models\Personnel;
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
            'sudin_id' => 'nullable|exists:sudins,id',
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
                'name.required' => 'Nama driver wajib diisi',
                'name.string' => 'Nama driver harus berupa teks',
                'name.max' => 'Nama driver maksimal 255 karakter',
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

    #[On('confirm-save-driver')]
    public function confirmSave()
    {
        Personnel::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id ?: null,
            'type' => 'driver',
        ]);
        $this->dispatch('close-modal', 'create-driver');
        $this->dispatch('success-created', message: 'Driver berhasil ditambahkan');
        $this->dispatch('driver-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.driver.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
