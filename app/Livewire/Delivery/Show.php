<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Delivery')]
    public Delivery $delivery;
    public function render()
    {
        return view('livewire.delivery.show');
    }
}
