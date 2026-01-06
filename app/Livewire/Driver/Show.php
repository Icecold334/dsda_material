<?php

namespace App\Livewire\Driver;

use Livewire\Component;
use App\Models\Personnel;
use Livewire\Attributes\Title;

class Show extends Component
{
    #[Title('Detail Driver')]

    public Personnel $driver;


    public function render()
    {
        return view('livewire.driver.show');
    }
}
