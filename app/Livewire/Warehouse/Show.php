<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Gudang')]

    public Warehouse $warehouse;


    public function render()
    {
        return view('livewire.warehouse.show');
    }
}
