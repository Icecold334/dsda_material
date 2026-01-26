<?php

namespace App\Livewire\Sudin;

use App\Models\Sudin;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class Edit extends Component
{
    public Sudin $sudin;
    public $name = '';
    public $short = '';
    public $address = '';

    public function mount()
    {
        $this->name = $this->sudin->name;
        $this->short = $this->sudin->short;
        $this->address = $this->sudin->address;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ];
    }

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'short' => $this->short,
                'address' => $this->address,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama sudin wajib diisi',
                'name.string' => 'Nama sudin harus berupa teks',
                'name.max' => 'Nama sudin maksimal 255 karakter',
                'short.string' => 'Singkatan harus berupa teks',
                'short.max' => 'Singkatan maksimal 50 karakter',
                'address.string' => 'Alamat harus berupa teks',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-update');
        return;
    }

    #[On('confirm-update-sudin')]
    public function confirmUpdate()
    {
        $this->sudin->update([
            'name' => $this->name,
            'short' => $this->short,
            'address' => $this->address,
        ]);

        $this->dispatch('close-modal', 'edit-sudin-' . $this->sudin->id);
        $this->dispatch('success-updated', message: 'Sudin berhasil diperbarui');
        $this->dispatch('sudin-updated');
    }

    public function render()
    {
        return view('livewire.sudin.edit');
    }
}
