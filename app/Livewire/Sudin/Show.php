<?php

namespace App\Livewire\Sudin;

use Livewire\Component;
use App\Models\Sudin;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Sudin')]

    public Sudin $sudin;


    public function render()
    {
        return view('livewire.sudin.show');
    }
}