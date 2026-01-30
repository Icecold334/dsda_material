<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Gudang')]

    public $data = [];
    public function mount()
    {
        $this->data = [
            ["name" => "Nama", "id" => "name", "width" => "30%"],
            ["name" => "Sudin", "id" => "sudin", "width" => "30%"],
            ["name" => "Lokasi", "id" => "location", "width" => "30%"],
            ["name" => "", "id" => "action", "width" => "10%"],
        ];
    }
    public $editWarehouseId = null;

    #[On('warehouse-created')]
    #[On('warehouse-updated')]
    #[On('deleteWarehouse')]
    public function refreshData()
    {
        $this->dispatch('refresh-grid');
    }

    public function editWarehouse($warehouseId)
    {
        $this->editWarehouseId = $warehouseId;
        $this->dispatch('open-modal', 'edit-warehouse-' . $warehouseId);
    }

    #[On('deleteWarehouse')]
    public function deleteWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            $warehouse->delete();
            $this->dispatch('success-deleted', message: 'Gudang berhasil dihapus');
            $this->dispatch('warehouse-deleted');
            $this->dispatch('refresh-grid');
        }
    }

    public function render()
    {
        return view('livewire.warehouse.index', [
            'warehouses' => Warehouse::all(),
        ]);
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Gudang',
        ];
    }
}
