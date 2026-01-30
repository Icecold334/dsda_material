<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('Daftar RAB')]

    public $data = [];

    public function mount()
    {
        $this->data = [
            ['name' => 'Nomor RAB', 'id' => 'nomor', 'width' => '70%'],
            ['name' => 'Status', 'id' => 'status', 'width' => '20%', 'className' => 'text-center'],
            ['name' => "", 'id' => 'action', 'width' => '10%']
        ];
    }

    public function render()
    {
        return view('livewire.rab.index');
    }
}
