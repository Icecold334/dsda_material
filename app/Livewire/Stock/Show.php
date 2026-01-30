<?php

namespace App\Livewire\Stock;

use App\Models\Warehouse;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail Stok Gudang')]

    public Warehouse $warehouse;

    public $data = [];

    public function render()
    {
        $this->warehouse->load('sudin');

        $this->data = [
            ["name" => "Nama Barang", "id" => "category", "width" => "15%"],
            ["name" => "Kode Spek", "id" => "code", "width" => "15%"],
            ["name" => "Spek", "id" => "spec", "width" => "20%"],
            ["name" => "Jumlah", "id" => "qty", "width" => "10%"],
            ["name" => "Satuan", "id" => "unit", "width" => "10%"],
            ["name" => "Status", "id" => "status", "width" => "10%", "sortable" => false]
        ];

        return view('livewire.stock.show');
    }
}
