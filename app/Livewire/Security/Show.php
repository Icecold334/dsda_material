<?php

namespace App\Livewire\Security;

use Livewire\Component;
use App\Models\Personnel;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Security')]

    public Personnel $security;


    public function render()
    {
        return view('livewire.security.show');
    }
}