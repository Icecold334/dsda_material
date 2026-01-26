<?php

namespace App\Livewire\Driver;

use App\Models\Personnel;
use App\Models\Sudin;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class Edit extends Component
{
    public Personnel $driver;
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->driver->name;
        $this->sudin_id = $this->driver->sudin_id;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'nullable|exists:sudins,id',
        ];
    }

    public function validateForm()
    {
        $validator =Validator::make(
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

        $this->dispatch('validation-passed-update');
        return;
    }

    #[On('confirm-update-driver')]
    public function confirmUpdate()
    {
        $this->driver->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id ?: null,
        ]);

        $this->dispatch('close-modal', 'edit-driver-' . $this->driver->id);
        $this->dispatch('success-updated', message: 'Driver berhasil diperbarui');
        $this->dispatch('driver-updated');
    }

    public function render()
    {
        return view('livewire.driver.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
