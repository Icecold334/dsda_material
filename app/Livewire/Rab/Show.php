<?php

namespace App\Livewire\Rab;

use App\Models\Rab;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Detail RAB')]
    public Rab $rab;
    public function render()
    {
        return view('livewire.rab.show');
    }
}
