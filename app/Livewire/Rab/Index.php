<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('Daftar RAB')]

    public function render()
    {
        return view('livewire.rab.index');
    }
}
