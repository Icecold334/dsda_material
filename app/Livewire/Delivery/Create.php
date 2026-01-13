<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\On;

class Create extends Component
{
    public $contractNumber, $isApi;
    public function mount()
    {
        $this->dispatch('open-modal', 'input-contract-number');
    }

    #[On("confirmContract")]
    public function confirmResult($data)
    {
        $this->dispatch('endLoading');
        $this->dispatch('open-modal', 'confirm-contract');
    }

    #[On("proceedCreateContract")]
    public function proceedCreateContract($data)
    {
        $this->isApi = isset($data['no_spk']);
        $this->contractNumber = $this->isApi ? $data['no_spk'] : $data['nomor'];
        if (!$this->isApi) {
            $this->dispatch('isNotApi');
        }
        // $this->dispatch('proceedCreateContractAgain', data: [
        //     'ContractData' => $data,
        //     'contractNumber' => $this->contractNumber,
        //     'apiExist' => $this->isApi,
        // ]);

    }
    public function render()
    {
        return view('livewire.delivery.create');
    }
}
