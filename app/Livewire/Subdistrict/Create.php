<?php

namespace App\Livewire\Subdistrict;

use App\Models\Subdistrict;
use App\Models\District;
use Livewire\Component;

class Create extends Component
{
    public District $district;
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
            'district_id' => $this->district->id,
            'sudin_id' => $this->district->sudin_id,
        ]);

        session()->flash('message', 'Subdistrict berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-subdistrict-' . $this->district->id);
        $this->dispatch('subdistrict-created');

        $this->reset('name');
    }

    public function render()
    {
        return view('livewire.subdistrict.create');
    }
}