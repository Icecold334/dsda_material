<?php

namespace App\Livewire\Stock;

use App\Models\Warehouse;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Stok Gudang')]

    public $data = [];

    public function mount()
    {
        $this->data = [
            ["name" => "Nama Sudin", "id" => "sudin", "width" => "30%"],
            ["name" => "Nama Gudang", "id" => "warehouse", "width" => "30%"],
            ["name" => "Jumlah Barang", "id" => "total_items", "width" => "20%"],
            ["name" => "", "id" => "action", "width" => "20%", "sortable" => false]
        ];
    }

    public function render()
    {
        return view('livewire.stock.index');
    }
}
