<?php

namespace App\Livewire\Subdistrict;

use App\Models\Subdistrict;
use Livewire\Component;

class Edit extends Component
{
    public Subdistrict $subdistrict;
    public $name = '';

    public function mount()
    {
        $this->name = $this->subdistrict->name;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->subdistrict->update([
            'name' => $this->name,
        ]);

        $this->dispatch('close-modal', 'edit-subdistrict-' . $this->subdistrict->id);
        $this->dispatch('success-updated', message: 'Kelurahan berhasil diperbarui');
        $this->dispatch('subdistrict-updated');
    }

    public function render()
    {
        return view('livewire.subdistrict.edit');
    }
}