<?php

namespace App\Livewire\InterSudinTransfer\Permintaan;

use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('Daftar Transfer Permintaan')]

    public $data = [];
    public function mount()
    {
        $this->data = [
            ["name" => "Tanggal", "id" => "tanggal", "width" => "12%"],
            ["name" => "Sudin Peminta", "id" => "sudin_pengirim", "width" => "22%"],
            ["name" => "Sudin Diminta", "id" => "sudin_penerima", "width" => "22%"],
            ["name" => "Pembuat", "id" => "user", "width" => "17%"],
            ["name" => "Status", "id" => "status", "width" => "12%", "className" => "text-center"],
            ["name" => "", "id" => "action", "width" => "10%"],
        ];
    }
    public function render()
    {
        return view('livewire.inter-sudin-transfer.permintaan.index');
    }
}
