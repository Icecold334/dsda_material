<?php

namespace App\Livewire\Subdistrict;

use App\Models\Subdistrict;
use App\Models\Division;
use Livewire\Component;

class Create extends Component
{
    public Division $district;  // tetap pakai nama $district untuk backward compatibility di views
    public $name = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        Subdistrict::create([
            'name' => $this->name,
            'division_id' => $this->district->id,  // kecamatan sekarang di divisions
            'sudin_id' => $this->district->sudin_id,
        ]);

        $this->dispatch('close-modal', 'create-subdistrict-' . $this->district->id);
        $this->dispatch('success-created', message: 'Kelurahan berhasil ditambahkan');
        $this->dispatch('subdistrict-created');

        $this->reset('name');
    }

    public function render()
    {
        return view('livewire.subdistrict.create');
    }
}