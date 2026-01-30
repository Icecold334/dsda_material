<?php

namespace App\Livewire\Delivery;

use Livewire\Component;

class Index extends Component
{
    public $data = [];

    public function render()
    {
        $this->data = [
            ["name" => "Nomor Pengiriman", "id" => "nomor", "width" => "50%"],
            ["name" => "Gudang Pengiriman", "id" => "gudang", "width" => "20%"],
            ["name" => "Status", "id" => "status", "width" => "20%", "className" => "text-center"],
            ["name" => "", "id" => "action", "width" => "10%", "className" => "text-center"]
        ];
        return view('livewire.delivery.index');
    }
}
