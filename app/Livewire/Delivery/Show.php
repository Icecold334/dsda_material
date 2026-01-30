<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Delivery')]

    public $data = [];

    public function mount()
    {
        $this->data = [
            ["name" => "Kode Barang", "id" => "kode", "width" => "15%"],
            ["name" => "Item", "id" => "item"],
            ["name" => "Jumlah", "id" => "qty", "width" => "15%", "className" => "text-right"],
            ["name" => "", "id" => "action", "width" => "10%", "sortable" => false, "className" => "text-center"]
        ];
    }

    public Delivery $delivery;
    public function render()
    {
        return view('livewire.delivery.show');
    }
}
