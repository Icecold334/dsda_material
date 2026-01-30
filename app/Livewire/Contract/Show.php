<?php

namespace App\Livewire\Contract;

use Livewire\Component;
use App\Models\Contract;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Kontrak')]

    public $data = [];

    public function mount()
    {
        $this->data = [
            ["name" => "Kode Barang", "id" => "code", "width" => "15%"],
            ["name" => "Item", "id" => "item"],
            ["name" => "Jumlah", "id" => "qty", "width" => "15%", "className" => "text-right"],
            ["name" => "", "id" => "action", "width" => "10%", "sortable" => false, "className" => "text-center"]
        ];
    }

    public Contract $contract;


    public function render()
    {
        return view('livewire.contract.show');
    }
}
