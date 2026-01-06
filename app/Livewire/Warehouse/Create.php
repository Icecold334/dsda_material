<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Models\Sudin;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $sudin_id = '';
    public $location = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        Warehouse::create([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'location' => $this->location,
        ]);

        session()->flash('message', 'Gudang berhasil ditambahkan.');

        $this->dispatch('close-modal', 'create-warehouse');
        $this->dispatch('warehouse-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.warehouse.create', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
