<?php

namespace App\Livewire\Driver;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Driver')]
    public function render()
    {
        return view('livewire.driver.index');
    }

    protected function layoutData()
    {
        return [
            'title' => 'Daftar Driver',
        ];
    }
}
