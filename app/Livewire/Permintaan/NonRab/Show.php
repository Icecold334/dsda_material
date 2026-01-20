<?php

namespace App\Livewire\Permintaan\NonRab;

use App\Models\RequestModel;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    public function sendRequest()
    {
        $this->permintaan->status = 'pending';
        $this->permintaan->save();

    }



    public function render()
    {
        return view('livewire.permintaan.non-rab.show');
    }
}
