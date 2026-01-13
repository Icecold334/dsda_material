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

    #[On("proceedCreateContract")]
    public function proceedCreateContract($data, $isApi)
    {
        // kalo ada prop no_spk berarti dari e-monev
        $this->isApi = $isApi;
        if (!$isApi) {
            $this->dispatch('isNotApi', contractNumber: $data['nomor']);
        } else {
            $this->dispatch('proceedCreateContractAgain', contractNumber: $data['no_spk'] ?? $data['nomor']);
            $this->dispatch('open-modal', 'choose-warehouse');
        }


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
