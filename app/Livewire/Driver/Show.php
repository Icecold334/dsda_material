<?php

namespace App\Livewire\Driver;

use Livewire\Component;
use App\Models\Driver;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Driver')]

    public Driver $driver;


    public function render()
    {
        return view('livewire.driver.show');
    }
}
