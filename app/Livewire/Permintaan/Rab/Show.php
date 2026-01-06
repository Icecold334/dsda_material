<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Component;
use App\Models\RequestModel;
use Livewire\Attributes\Title;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;
    public function render()
    {
        return view('livewire.permintaan.rab.show');
    }
}
