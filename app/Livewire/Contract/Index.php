<?php

namespace App\Livewire\Contract;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Kontrak')]
    public function render()
    {
        return view('livewire.contract.index');
    }

}
