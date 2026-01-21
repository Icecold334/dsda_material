<?php

namespace App\Livewire\District;

use App\Models\Division;
use App\Models\Sudin;
use Livewire\Component;

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

    public function save()
    {
        $this->validate();

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