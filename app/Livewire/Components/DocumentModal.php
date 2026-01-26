<?php

namespace App\Livewire\Components;

use Livewire\Component;

class DocumentModal extends Component
{
    public $permintaanId;
    public $modalId = 'document-modal';

    public function mount($permintaanId)
    {
        $this->permintaanId = $permintaanId;
    }

    public function render()
    {
        return view('livewire.components.document-modal');
    }
}
