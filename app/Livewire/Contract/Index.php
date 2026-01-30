<?php

namespace App\Livewire\Contract;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Kontrak')]
    public $data;
    public function mount()
    {
        $this->data = [
            ["name" => "Nomor Kontrak", "id" => "nomor", "width" => "70%"],
            ["name" => "Terdaftar Pada E-Monev", "id" => "status", "width" => "20%", "className" => "text-center"],
            ["name" => "", "id" => "action", "width" => "10%", "className" => "text-center"]
        ];
    }
    public function render()
    {
        return view('livewire.contract.index');
    }

}
