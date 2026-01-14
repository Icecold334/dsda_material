<?php

namespace App\Livewire\Delivery;

use App\Models\Warehouse;
use Livewire\Component;

class ChooseWarehouse extends Component
{
    public $warehouses;

    public function mount()
    {
        $this->warehouses = Warehouse::all();
    }

    public function render()
    {
        return view('livewire.delivery.choose-warehouse');
    }
}
