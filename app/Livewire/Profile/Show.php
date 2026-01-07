<?php

namespace App\Livewire\Profile;

use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{
    #[Title('Profil Saya')]

    public function render()
    {
        return view('livewire.profile.show');
    }
}
