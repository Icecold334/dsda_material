<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Permintaan')]
    public $data = [];
    public function mount()
    {
        $this->data = [
            ["name" => "Nomor Permintaan", "id" => "nomor", "width" => "20%"],
            ["name" => "Pemohon", "id" => "user", "width" => "30%"],
            ["name" => "Status", "id" => "status", "width" => "20%", "className" => "text-center"],
            ["name" => "", "id" => "action", "width" => "10%"]
        ];
    }
    public function render()
    {
        return view('livewire.permintaan.rab.index');
    }
}
