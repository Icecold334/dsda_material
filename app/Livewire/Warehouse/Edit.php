<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Models\Sudin;
use Livewire\Component;

class Edit extends Component
{
    public Warehouse $warehouse;
    public $name = '';
    public $sudin_id = '';
    public $location = '';

    public function mount()
    {
        $this->name = $this->warehouse->name;
        $this->sudin_id = $this->warehouse->sudin_id;
        $this->location = $this->warehouse->location;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sudin_id' => 'required|exists:sudins,id',
            'location' => 'nullable|string|max:255',
        ];
    }

    #[\Livewire\Attributes\On('confirmUpdateWarehouse')]
    public function update($warehouseId = null)
    {
        $this->validate();

        $this->warehouse->update([
            'name' => $this->name,
            'sudin_id' => $this->sudin_id,
            'location' => $this->location,
        ]);

        $this->dispatch('close-modal', 'edit-warehouse-' . $this->warehouse->id);
        $this->dispatch('warehouse-updated');
    }

    public function render()
    {
        return view('livewire.warehouse.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
        ]);
    }
}
