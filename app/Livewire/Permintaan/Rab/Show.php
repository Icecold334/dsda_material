<?php

namespace App\Livewire\Permintaan\Rab;

use Livewire\Component;
use App\Models\RequestModel;
use Livewire\Attributes\Title;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    public function mount()
    {
        $this->dispatch('setRequestDataById', [
            'permintaan_id' => $this->permintaan->id,
            'is_rab' => true
        ]);
    }

    public function render()
    {
        return view('livewire.permintaan.rab.show');
    }
}