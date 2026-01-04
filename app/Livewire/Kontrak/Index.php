<?php

namespace App\Livewire\Kontrak;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Daftar Kontrak')]
    public function render()
    {
        return view('livewire.kontrak.index');
    }

    protected function layoutData()
    {
        return [
            'title' => 'Approval Kontrak',
        ];
    }
}
