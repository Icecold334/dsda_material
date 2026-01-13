<?php

namespace App\Livewire\Delivery;

use Livewire\Component;

class ChooseWarehouse extends Component
{
    public $warehouses;

    public function render()
    {
        return view('livewire.delivery.choose-warehouse');
    }
}
