<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Pengguna')]

    public User $user;

    public function render()
    {
        return view('livewire.user.show');
    }
}
