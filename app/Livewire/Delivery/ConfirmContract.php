<?php

namespace App\Livewire\Delivery;

use App\Models\Contract;
use App\Models\ContractAmendment;
use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmContract extends Component
{

    public $contractData = [], $showDetail = false, $titleModal = '', $isApi = false, $contract, $amendmentId = null;

    #[On('confirmContract')]
    public function confirmContract(Contract $contract, $data = [], $amendment = null)
    {
        $this->showDetail = false;
        $this->changeTitleModal();
        $this->contractData = $data;
        $this->contract = $contract;
        $this->amendmentId = $amendment;
        $this->isApi = $contract->is_api;
    }

    #[On("proceedCreateContract")]
    public function proceedCreateContract($data, $isApi)
    {
        // kalo ada prop no_spk berarti dari e-monev
        $this->isApi = $isApi;
        if (!$isApi) {
            $this->dispatch('isNotApi', contract: $this->contract->id);
        } else {
            $this->dispatch('proceedCreateContractAgain', contract: $this->contract->id);
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
