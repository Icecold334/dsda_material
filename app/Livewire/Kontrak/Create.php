<?php

namespace App\Livewire\Kontrak;

use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    #[Title("Tambah Kontrak")]

    public function render()
    {
        $this->dispatch('open_modal');
        return view('livewire.kontrak.create');
    }
}
