<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Component;

class WarehouseSelectionModal extends Component
{
    public $show = false;
    public $warehousesWithStock = [];
    public $items = [];

    protected $listeners = ['openWarehouseModal', 'closeWarehouseModal'];

    public function openWarehouseModal($data)
    {
        $this->warehousesWithStock = $data['warehouses'];
        $this->items = $data['items'];
        $this->show = true;
        $this->dispatch('open-modal', 'warehouse-selection');
    }

    public function closeWarehouseModal()
    {
        $this->show = false;
        $this->dispatch('close-modal', 'warehouse-selection');
    }

    public function selectWarehouse($warehouseId)
    {
        $this->dispatch('warehouseSelected', warehouseId: $warehouseId);
        $this->closeWarehouseModal();
    }

    public function render()
    {
        return view('livewire.permintaan.rab.warehouse-selection-modal');
    }
}
