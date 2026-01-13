<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Title('Tambah Pengiriman')]
    public $contractNumber, $isApi = true, $warehouse;
    public function mount()
    {
        $this->dispatch('open-modal', 'input-contract-number');
        // $this->contractNumber = '20397/PN01.02';
    }

    #[On("confirmContract")]
    public function confirmResult($data)
    {
        $this->dispatch('endLoading');
        $this->dispatch('open-modal', 'confirm-contract');
    }

    #[On('proceedCreateContractAgain')]
    public function confirmContractAgain($contractNumber)
    {
        $this->contractNumber = $contractNumber;
    }

    public function render()
    {
        return view('livewire.delivery.create');
    }
}
