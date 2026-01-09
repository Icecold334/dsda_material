<?php

namespace App\Livewire\Contract;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmContractApi extends Component
{
    public $dataContract = [], $showDetail = false, $titleModal = '';
    public function mount()
    {
        $this->changeTitleModal();
    }

    #[On("confirmContract")]
    public function confirmContract($data)
    {
        $this->showDetail = false;
        $this->changeTitleModal();
        $this->dataContract = $data['dataContract'];
    }
    #[On("contractDetail")]
    public function openDetailContract()
    {
        $this->showDetail = true;
        $this->changeTitleModal();
        $this->dispatch('open-modal', 'confirm-contract-api');
    }

    private function changeTitleModal()
    {
        if ($this->showDetail) {
            $this->titleModal = 'Detail Kontrak ' . ($this->dataContract['no_spk'] ?? '');
        } else {
            $this->titleModal = 'Konfirmasi Kontrak';
        }
    }
    public function render()
    {
        return view('livewire.contract.confirm-contract-api');
    }
}
