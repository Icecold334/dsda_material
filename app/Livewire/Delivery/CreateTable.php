<?php

namespace App\Livewire\Delivery;

use Livewire\Component;

class CreateTable extends Component
{
    public $listBarang = [];
    public function render()
    {
        return view('livewire.delivery.create-table');
    }
}
