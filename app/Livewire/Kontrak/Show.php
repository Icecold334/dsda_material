<?php

namespace App\Livewire\Kontrak;

use Livewire\Component;
use App\Models\Contract;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Kontrak')]

    public Contract $contract;


    public function render()
    {
        return view('livewire.kontrak.show');
    }
}
