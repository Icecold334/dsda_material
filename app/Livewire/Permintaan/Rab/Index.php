<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Permintaan')]
    public function render()
    {
        return view('livewire.permintaan.rab.index');
    }
}
