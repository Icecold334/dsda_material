<?php

namespace App\Livewire\Components;

use App\Models\RequestModel;
use Livewire\Component;

class DocumentModal extends Component
{
    public $permintaanId;
    public $modalId = 'document-modal';
    public $isRab = false;

    public function mount($permintaanId)
    {
        $this->permintaanId = $permintaanId;

        // Auto detect apakah RAB atau Non-RAB
        $permintaan = RequestModel::find($permintaanId);
        if ($permintaan) {
            $this->isRab = !is_null($permintaan->rab_id);
        }
    }

    public function render()
    {
        return view('livewire.components.document-modal');
    }
}
