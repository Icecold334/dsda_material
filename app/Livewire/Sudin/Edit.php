<?php

namespace App\Livewire\Sudin;

use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public Sudin $sudin;
    public $name = '';
    public $short = '';
    public $address = '';
    public $postal_code = '';
    public $phone = '';

    public function mount()
    {
        $this->name = $this->sudin->name;
        $this->short = $this->sudin->short;
        $this->address = $this->sudin->address;
        $this->postal_code = $this->sudin->postal_code;
        $this->phone = $this->sudin->phone;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->sudin->update([
            'name' => $this->name,
            'short' => $this->short,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
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