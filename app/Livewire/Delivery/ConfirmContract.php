<?php

namespace App\Livewire\Delivery;

use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmContract extends Component
{

    public $contractData = [], $showDetail = false, $titleModal = '', $isApi = false;

    #[On('confirmContract')]
    public function confirmContract($data)
    {
        $this->showDetail = false;
        $this->changeTitleModal();
        $this->contractData = $data['contractData'];
        $this->isApi = $data['isApi'];
    }
    private function changeTitleModal()
    {
        if ($this->showDetail) {
            $this->titleModal = 'Detail Kontrak ' . ($this->contractData['no_spk'] ?? '');
        } else {
            $this->titleModal = 'Konfirmasi Kontrak';
        }
    }
    public function render()
    {
        return view('livewire.delivery.confirm-contract');
    }
}
