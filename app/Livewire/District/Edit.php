<?php

namespace App\Livewire\District;

use App\Models\Division;
use App\Models\Sudin;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class Edit extends Component
{
    public Division $district;  // tetap pakai nama $district untuk backward compatibility
    public $name = '';
    public $sudin_id = '';

    public function mount()
    {
        $this->name = $this->district->name;
        $this->sudin_id = $this->district->sudin_id;
    }

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

        $this->dispatch('validation-passed-update');
        return;
    }

    #[On('confirm-update-district')]
    public function confirmUpdate()
    {
        $this->district->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
        ]);

        $this->dispatch('close-modal', 'edit-district-' . $this->district->id);
        $this->dispatch('success-updated', message: 'Kecamatan berhasil diperbarui');
        $this->dispatch('district-updated');
    }

    public function render()
    {
        return view('livewire.district.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
